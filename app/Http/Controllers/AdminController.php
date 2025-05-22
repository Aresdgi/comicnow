<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Comic;
use App\Models\Autor;
use App\Models\Usuario;
use App\Models\Pedido;
use App\Models\Resena;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class AdminController extends Controller
{
    /**
     * Constructor.
     */
    public function __construct()
    {
        // No usamos middleware aquí para evitar problemas
    }
    
    /**
     * Verifica si el usuario es administrador.
     */
    private function checkAdmin()
    {
        if (!Auth::check() || Auth::user()->rol !== 'admin') {
            return false;
        }
        return true;
    }
    
    /**
     * Muestra el panel de control admin con estadísticas generales
     */
    public function dashboard()
    {
        if (!$this->checkAdmin()) {
            return redirect('/')->with('error', 'Acceso denegado. Se requieren privilegios de administrador.');
        }

        // Obtener estadísticas para el dashboard
        $stats = [
            'total_comics' => Comic::count(),
            'total_autores' => Autor::count(),
            'total_usuarios' => Usuario::count(),
            'total_pedidos' => Pedido::count(),
            'ingresos' => Pedido::where('estado', 'completado')->sum('total'),
            'pedidos_pendientes' => Pedido::where('estado', 'pendiente')->count(),
            'comics_sin_stock' => Comic::where('stock', 0)->count(),
            'resenas_nuevas' => Resena::orderBy('created_at', 'desc')->take(5)->get()
        ];
        
        // Obtener los cómics más vendidos
        $comics_populares = DB::table('comics')
            ->join('detalle_pedidos', 'comics.id_comic', '=', 'detalle_pedidos.id_comic')
            ->join('pedidos', 'detalle_pedidos.id_pedido', '=', 'pedidos.id_pedido')
            ->where('pedidos.estado', 'completado')
            ->select('comics.id_comic', 'comics.titulo', 'comics.imagen', DB::raw('SUM(detalle_pedidos.cantidad) as total_vendidos'))
            ->groupBy('comics.id_comic', 'comics.titulo', 'comics.imagen')
            ->orderBy('total_vendidos', 'desc')
            ->take(5)
            ->get();
        
        return view('admin.dashboard', compact('stats', 'comics_populares'));
    }
    
    /**
     * Muestra la lista de pedidos para administrar
     */
    public function pedidos()
    {
        if (!$this->checkAdmin()) {
            return redirect('/')->with('error', 'Acceso denegado. Se requieren privilegios de administrador.');
        }

        $pedidos = Pedido::with(['usuario', 'detalles.comic'])
            ->orderBy('created_at', 'desc')
            ->paginate(10);
            
        return view('admin.pedidos', compact('pedidos'));
    }
    
    /**
     * Muestra detalles de un pedido específico
     */
    public function detallePedido($id)
    {
        if (!$this->checkAdmin()) {
            return redirect('/')->with('error', 'Acceso denegado. Se requieren privilegios de administrador.');
        }

        $pedido = Pedido::with(['usuario', 'detalles.comic'])
            ->findOrFail($id);
            
        return view('admin.detalle-pedido', compact('pedido'));
    }
    
    /**
     * Actualiza el estado de un pedido
     */
    public function actualizarEstadoPedido(Request $request, $id)
    {
        if (!$this->checkAdmin()) {
            return redirect('/')->with('error', 'Acceso denegado. Se requieren privilegios de administrador.');
        }

        $request->validate([
            'estado' => 'required|in:pendiente,procesando,enviado,completado,cancelado'
        ]);
        
        $pedido = Pedido::findOrFail($id);
        $pedido->estado = $request->estado;
        $pedido->save();
        
        // Si el pedido es cancelado, devolvemos el stock
        if ($request->estado === 'cancelado' && $pedido->isDirty('estado')) {
            foreach ($pedido->detalles as $detalle) {
                $comic = $detalle->comic;
                $comic->stock += $detalle->cantidad;
                $comic->save();
            }
        }
        
        return redirect()->back()->with('success', 'Estado del pedido actualizado correctamente.');
    }
    
    /**
     * Muestra las estadísticas de ventas
     */
    public function estadisticas()
    {
        if (!$this->checkAdmin()) {
            return redirect('/')->with('error', 'Acceso denegado. Se requieren privilegios de administrador.');
        }

        // Ventas por mes (último año)
        $ventas_mensuales = DB::table('pedidos')
            ->select(DB::raw('MONTH(created_at) as mes, YEAR(created_at) as año, SUM(total) as total_ventas'))
            ->where('estado', 'completado')
            ->whereRaw('created_at >= DATE_SUB(NOW(), INTERVAL 1 YEAR)')
            ->groupBy('año', 'mes')
            ->orderBy('año')
            ->orderBy('mes')
            ->get();
        
        // Categorías más vendidas
        $categorias_populares = DB::table('comics')
            ->join('detalle_pedidos', 'comics.id_comic', '=', 'detalle_pedidos.id_comic')
            ->join('pedidos', 'detalle_pedidos.id_pedido', '=', 'pedidos.id_pedido')
            ->where('pedidos.estado', 'completado')
            ->select('comics.categoria', DB::raw('SUM(detalle_pedidos.cantidad) as total_vendidos'))
            ->groupBy('comics.categoria')
            ->orderBy('total_vendidos', 'desc')
            ->get();
        
        return view('admin.estadisticas', compact('ventas_mensuales', 'categorias_populares'));
    }
    
    /**
     * Muestra la lista de usuarios registrados
     */
    public function usuarios()
    {
        if (!$this->checkAdmin()) {
            return redirect('/')->with('error', 'Acceso denegado. Se requieren privilegios de administrador.');
        }

        $usuarios = Usuario::orderBy('created_at', 'desc')->paginate(15);
        return view('admin.usuarios', compact('usuarios'));
    }
    
    /**
     * Cambia el rol de un usuario
     */
    public function cambiarRolUsuario(Request $request, $id)
    {
        if (!$this->checkAdmin()) {
            return redirect('/')->with('error', 'Acceso denegado. Se requieren privilegios de administrador.');
        }

        $request->validate([
            'rol' => 'required|in:usuario,admin'
        ]);
        
        $usuario = Usuario::findOrFail($id);
        $usuario->rol = $request->rol;
        $usuario->save();
        
        return redirect()->back()->with('success', 'Rol de usuario actualizado correctamente.');
    }
    
    /**
     * Muestra el formulario para editar un cómic.
     */
    public function comicEditar($id)
    {
        if (!$this->checkAdmin()) {
            return redirect('/')->with('error', 'Acceso denegado. Se requieren privilegios de administrador.');
        }

        $comic = Comic::findOrFail($id);
        $autores = Autor::all();
        
        return view('admin.comics.edit', compact('comic', 'autores'));
    }
    
    /**
     * Actualiza un cómic existente en la base de datos.
     */
    public function comicUpdate(Request $request, $id)
    {
        if (!$this->checkAdmin()) {
            return redirect('/')->with('error', 'Acceso denegado. Se requieren privilegios de administrador.');
        }

        $request->validate([
            'titulo' => 'required|string|max:255',
            'id_autor' => 'required|exists:autores,id_autor',
            'descripcion' => 'required|string',
            'precio' => 'required|numeric|min:0',
            'stock' => 'required|integer|min:0',
            'portada_url' => 'nullable|image|max:2048',
            'archivo_comic' => 'nullable|file|mimes:pdf,cbz,cbr|max:20480', // 20MB max
        ]);

        $comic = Comic::findOrFail($id);
        
        // Actualizar los datos básicos
        $comic->titulo = $request->titulo;
        $comic->id_autor = $request->id_autor;
        $comic->descripcion = $request->descripcion;
        $comic->precio = $request->precio;
        $comic->stock = $request->stock;

        // Manejar la portada si se sube una nueva
        if ($request->hasFile('portada_url')) {
            // Eliminar la portada anterior si existe
            if ($comic->portada_url && Storage::disk('public')->exists($comic->portada_url)) {
                Storage::disk('public')->delete($comic->portada_url);
            }
            $portadaPath = $request->file('portada_url')->store('portadas', 'public');
            $comic->portada_url = $portadaPath;
        }

        // Manejar el archivo si se sube uno nuevo
        if ($request->hasFile('archivo_comic')) {
            // Eliminar el archivo anterior si existe
            if ($comic->archivo_comic && Storage::disk('public')->exists($comic->archivo_comic)) {
                Storage::disk('public')->delete($comic->archivo_comic);
            }
            $archivoPath = $request->file('archivo_comic')->store('comics', 'public');
            $comic->archivo_comic = $archivoPath;
        }

        $comic->save();

        return redirect()->route('admin.comics')->with('success', 'Cómic actualizado exitosamente');
    }
    
    /**
     * Elimina un cómic de la base de datos.
     */
    public function comicDestroy($id)
    {
        if (!$this->checkAdmin()) {
            return redirect('/')->with('error', 'Acceso denegado. Se requieren privilegios de administrador.');
        }

        $comic = Comic::findOrFail($id);
        
        // Comprobar si hay reseñas, pedidos o biblioteca relacionados antes de eliminar
        if ($comic->resenas()->count() > 0 || $comic->detallePedidos()->count() > 0 || $comic->biblioteca()->count() > 0) {
            return back()->with('error', 'No se puede eliminar el cómic porque tiene reseñas, pedidos o está en bibliotecas de usuarios');
        }
        
        // Eliminar archivos asociados
        if ($comic->portada_url && Storage::disk('public')->exists($comic->portada_url)) {
            Storage::disk('public')->delete($comic->portada_url);
        }
        
        if ($comic->archivo_comic && Storage::disk('public')->exists($comic->archivo_comic)) {
            Storage::disk('public')->delete($comic->archivo_comic);
        }
        
        $comic->delete();

        return redirect()->route('admin.comics')->with('success', 'Cómic eliminado exitosamente');
    }
    
    /**
     * Muestra la lista de todos los cómics para administración.
     */
    public function comics()
    {
        if (!$this->checkAdmin()) {
            return redirect('/')->with('error', 'Acceso denegado. Se requieren privilegios de administrador.');
        }

        $comics = Comic::with('autor')->get();
        return view('admin.comics.index', compact('comics'));
    }
    
    /**
     * Muestra el formulario para crear un nuevo cómic.
     */
    public function comicCrear()
    {
        if (!$this->checkAdmin()) {
            return redirect('/')->with('error', 'Acceso denegado. Se requieren privilegios de administrador.');
        }

        $autores = Autor::all();
        return view('admin.comics.create', compact('autores'));
    }
    
    /**
     * Almacena un nuevo cómic en la base de datos.
     */
    public function comicStore(Request $request)
    {
        if (!$this->checkAdmin()) {
            return redirect('/')->with('error', 'Acceso denegado. Se requieren privilegios de administrador.');
        }

        $request->validate([
            'titulo' => 'required|string|max:255',
            'id_autor' => 'required|exists:autores,id_autor',
            'descripcion' => 'required|string',
            'precio' => 'required|numeric|min:0',
            'stock' => 'required|integer|min:0',
            'portada_url' => 'nullable|image|max:2048',
            'archivo_comic' => 'nullable|file|mimes:pdf,cbz,cbr|max:20480', // 20MB max
        ]);

        $comic = new Comic();
        $comic->titulo = $request->titulo;
        $comic->id_autor = $request->id_autor;
        $comic->descripcion = $request->descripcion;
        $comic->precio = $request->precio;
        $comic->stock = $request->stock;

        // Manejar la imagen de portada
        if ($request->hasFile('portada_url')) {
            $portadaPath = $request->file('portada_url')->store('portadas', 'public');
            $comic->portada_url = $portadaPath;
        }

        // Manejar el archivo del cómic
        if ($request->hasFile('archivo_comic')) {
            $archivoPath = $request->file('archivo_comic')->store('comics', 'public');
            $comic->archivo_comic = $archivoPath;
        }

        $comic->save();

        return redirect()->route('admin.comics')->with('success', 'Cómic creado exitosamente');
    }
}
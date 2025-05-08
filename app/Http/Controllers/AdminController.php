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
     * Constructor que verifica que solo los administradores puedan acceder
     */
    public function __construct()
    {
        $this->middleware(function ($request, $next) {
            if (!Auth::check() || Auth::user()->rol !== 'admin') {
                return redirect('/login')->with('error', 'Acceso denegado. Se requieren privilegios de administrador.');
            }
            
            return $next($request);
        });
    }
    
    /**
     * Muestra el panel de control admin con estadísticas generales
     */
    public function dashboard()
    {
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
        $pedido = Pedido::with(['usuario', 'detalles.comic'])
            ->findOrFail($id);
            
        return view('admin.detalle-pedido', compact('pedido'));
    }
    
    /**
     * Actualiza el estado de un pedido
     */
    public function actualizarEstadoPedido(Request $request, $id)
    {
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
        $usuarios = Usuario::orderBy('created_at', 'desc')->paginate(15);
        return view('admin.usuarios', compact('usuarios'));
    }
    
    /**
     * Cambia el rol de un usuario
     */
    public function cambiarRolUsuario(Request $request, $id)
    {
        $request->validate([
            'rol' => 'required|in:usuario,admin'
        ]);
        
        $usuario = Usuario::findOrFail($id);
        $usuario->rol = $request->rol;
        $usuario->save();
        
        return redirect()->back()->with('success', 'Rol de usuario actualizado correctamente.');
    }
}
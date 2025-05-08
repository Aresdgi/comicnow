<?php

namespace App\Http\Controllers;

use App\Models\Usuario;
use App\Models\Biblioteca;
use App\Models\Pedido;
use App\Models\Resena;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UsuarioController extends Controller
{
    /**
     * Muestra una lista de todos los usuarios.
     */
    public function index()
    {
        $usuarios = Usuario::all();
        return view('usuarios.index', compact('usuarios'));
    }

    /**
     * Muestra el formulario para crear un nuevo usuario.
     */
    public function create()
    {
        return view('usuarios.create');
    }

    /**
     * Almacena un usuario recién creado en la base de datos.
     */
    public function store(Request $request)
    {
        $request->validate([
            'nombre' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:usuarios',
            'contraseña' => 'required|string|min:8|confirmed',
            'direccion' => 'nullable|string|max:255',
            'preferencias' => 'nullable|string',
            'rol' => 'required|string|in:admin,cliente',
        ]);

        Usuario::create([
            'nombre' => $request->nombre,
            'email' => $request->email,
            'contraseña' => Hash::make($request->contraseña),
            'direccion' => $request->direccion,
            'preferencias' => $request->preferencias,
            'rol' => $request->rol,
        ]);

        return redirect()->route('usuarios.index')
            ->with('success', 'Usuario creado exitosamente');
    }

    /**
     * Muestra el perfil del usuario especificado.
     */
    public function show(Usuario $usuario)
    {
        // Obtener estadísticas adicionales
        $totalPedidos = Pedido::where('id_usuario', $usuario->id_usuario)->count();
        $totalResenas = Resena::where('id_usuario', $usuario->id_usuario)->count();
        $totalEnBiblioteca = Biblioteca::where('id_usuario', $usuario->id_usuario)->count();
        
        return view('usuarios.show', compact('usuario', 'totalPedidos', 'totalResenas', 'totalEnBiblioteca'));
    }

    /**
     * Muestra el formulario para editar el usuario especificado.
     */
    public function edit(Usuario $usuario)
    {
        return view('usuarios.edit', compact('usuario'));
    }

    /**
     * Actualiza el usuario especificado en la base de datos.
     */
    public function update(Request $request, Usuario $usuario)
    {
        $request->validate([
            'nombre' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:usuarios,email,'.$usuario->id_usuario.',id_usuario',
            'direccion' => 'nullable|string|max:255',
            'preferencias' => 'nullable|string',
            'rol' => 'required|string|in:admin,cliente',
        ]);

        $data = $request->all();
        
        // Solo actualizar la contraseña si se proporciona una nueva
        if ($request->filled('contraseña')) {
            $request->validate([
                'contraseña' => 'required|string|min:8|confirmed',
            ]);
            $data['contraseña'] = Hash::make($request->contraseña);
        } else {
            unset($data['contraseña']);
        }

        $usuario->update($data);

        return redirect()->route('usuarios.index')
            ->with('success', 'Usuario actualizado exitosamente');
    }

    /**
     * Elimina el usuario especificado de la base de datos.
     */
    public function destroy(Usuario $usuario)
    {
        // Verificar si el usuario tiene pedidos o reseñas antes de eliminar
        $tienePedidos = Pedido::where('id_usuario', $usuario->id_usuario)->exists();
        $tieneResenas = Resena::where('id_usuario', $usuario->id_usuario)->exists();
        
        if ($tienePedidos || $tieneResenas) {
            return back()->with('error', 'No se puede eliminar el usuario porque tiene pedidos o reseñas asociadas');
        }
        
        // Eliminar entradas de biblioteca
        Biblioteca::where('id_usuario', $usuario->id_usuario)->delete();
        
        // Eliminar usuario
        $usuario->delete();

        return redirect()->route('usuarios.index')
            ->with('success', 'Usuario eliminado exitosamente');
    }
    
    /**
     * Muestra el panel de control del usuario.
     */
    public function dashboard(Usuario $usuario)
    {
        $pedidosRecientes = Pedido::where('id_usuario', $usuario->id_usuario)
                                ->with('detalles.comic')
                                ->latest('fecha')
                                ->take(5)
                                ->get();
                                
        $biblioteca = Biblioteca::where('id_usuario', $usuario->id_usuario)
                             ->with('comic')
                             ->get();
                             
        $resenas = Resena::where('id_usuario', $usuario->id_usuario)
                       ->with('comic')
                       ->latest('fecha')
                       ->take(5)
                       ->get();
        
        return view('usuarios.dashboard', compact('usuario', 'pedidosRecientes', 'biblioteca', 'resenas'));
    }
}
<?php

namespace App\Http\Controllers;

use App\Models\Biblioteca;
use App\Models\Comic;
use App\Models\Usuario;
use App\Models\Resena;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class BibliotecaController extends Controller
{
    /**
     * Muestra la biblioteca del usuario autenticado.
     */
    public function index()
    {
        $usuario = Auth::user();
        $biblioteca = Biblioteca::where('id_usuario', $usuario->id_usuario)
                              ->with('comic')
                              ->get();
                              
        return view('bibliotecas.index', compact('biblioteca', 'usuario'));
    }

    /**
     * Almacena un nuevo comic en la biblioteca del usuario.
     */
    public function store(Request $request)
    {
        $usuario = Auth::user();
        
        $request->validate([
            'id_comic' => 'required|exists:comics,id_comic',
        ]);

        // Verificar si el comic ya está en la biblioteca
        $existente = Biblioteca::where('id_usuario', $usuario->id_usuario)
                             ->where('id_comic', $request->id_comic)
                             ->first();

        if ($existente) {
            return redirect()->back()
                ->with('error', 'Este comic ya está en tu biblioteca.')
                ->withInput();
        }

        Biblioteca::create([
            'id_usuario' => $usuario->id_usuario,
            'id_comic' => $request->id_comic,
            'progreso_lectura' => 0.00,
            'ultimo_marcador' => 0,
        ]);

        return redirect()->route('biblioteca.index')
            ->with('success', 'Comic añadido a tu biblioteca');
    }

    /**
     * Muestra la vista de lectura de un comic.
     */
    public function leer($id_comic)
    {
        $usuario = Auth::user();
        $entrada = Biblioteca::where('id_usuario', $usuario->id_usuario)
                           ->where('id_comic', $id_comic)
                           ->firstOrFail();
        
        $comic = Comic::with(['resenas.usuario', 'autor'])->findOrFail($id_comic);
        
        // Verificar si el usuario ya ha reseñado este cómic
        $resenaUsuario = Resena::where('id_usuario', $usuario->id_usuario)
                              ->where('id_comic', $id_comic)
                              ->first();
        
        // Calcular promedio de valoraciones
        $promedioValoracion = $comic->resenas->avg('valoracion');
        $totalResenas = $comic->resenas->count();
        
        return view('bibliotecas.leer', compact('entrada', 'comic', 'resenaUsuario', 'promedioValoracion', 'totalResenas'));
    }



    /**
     * Elimina un comic de la biblioteca del usuario.
     */
    public function destroy($id_comic)
    {
        $usuario = Auth::user();
        $entrada = Biblioteca::where('id_usuario', $usuario->id_usuario)
                           ->where('id_comic', $id_comic)
                           ->firstOrFail();
        
        $entrada->delete();

        return redirect()->route('biblioteca.index')
            ->with('success', 'Comic eliminado de tu biblioteca');
    }
}
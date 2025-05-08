<?php

namespace App\Http\Controllers;

use App\Models\Biblioteca;
use App\Models\Comic;
use App\Models\Usuario;
use Illuminate\Http\Request;

class BibliotecaController extends Controller
{
    /**
     * Muestra la biblioteca del usuario autenticado.
     */
    public function index($id_usuario)
    {
        $usuario = Usuario::findOrFail($id_usuario);
        $biblioteca = Biblioteca::where('id_usuario', $id_usuario)
                              ->with('comic')
                              ->get();
                              
        return view('biblioteca.index', compact('biblioteca', 'usuario'));
    }

    /**
     * Almacena un nuevo comic en la biblioteca del usuario.
     */
    public function store(Request $request)
    {
        $request->validate([
            'id_usuario' => 'required|exists:usuarios,id_usuario',
            'id_comic' => 'required|exists:comics,id_comic',
        ]);

        // Verificar si el comic ya está en la biblioteca
        $existente = Biblioteca::where('id_usuario', $request->id_usuario)
                             ->where('id_comic', $request->id_comic)
                             ->first();

        if ($existente) {
            return redirect()->back()
                ->with('error', 'Este comic ya está en tu biblioteca.')
                ->withInput();
        }

        Biblioteca::create([
            'id_usuario' => $request->id_usuario,
            'id_comic' => $request->id_comic,
            'progreso_lectura' => 0.00,
            'ultimo_marcador' => '',
        ]);

        return redirect()->route('biblioteca.index', ['id_usuario' => $request->id_usuario])
            ->with('success', 'Comic añadido a tu biblioteca');
    }

    /**
     * Muestra la vista de lectura de un comic.
     */
    public function leer($id_usuario, $id_comic)
    {
        $entrada = Biblioteca::where('id_usuario', $id_usuario)
                           ->where('id_comic', $id_comic)
                           ->firstOrFail();
        
        $comic = Comic::findOrFail($id_comic);
        
        return view('biblioteca.leer', compact('entrada', 'comic'));
    }

    /**
     * Actualiza el progreso de lectura de un comic.
     */
    public function actualizarProgreso(Request $request, $id_usuario, $id_comic)
    {
        $request->validate([
            'progreso_lectura' => 'required|numeric|min:0|max:100',
            'ultimo_marcador' => 'required|string',
        ]);

        $entrada = Biblioteca::where('id_usuario', $id_usuario)
                           ->where('id_comic', $id_comic)
                           ->firstOrFail();
        
        $entrada->update([
            'progreso_lectura' => $request->progreso_lectura,
            'ultimo_marcador' => $request->ultimo_marcador,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Progreso guardado correctamente'
        ]);
    }

    /**
     * Elimina un comic de la biblioteca del usuario.
     */
    public function destroy($id_usuario, $id_comic)
    {
        $entrada = Biblioteca::where('id_usuario', $id_usuario)
                           ->where('id_comic', $id_comic)
                           ->firstOrFail();
        
        $entrada->delete();

        return redirect()->route('biblioteca.index', ['id_usuario' => $id_usuario])
            ->with('success', 'Comic eliminado de tu biblioteca');
    }
}
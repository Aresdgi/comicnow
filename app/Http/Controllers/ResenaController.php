<?php

namespace App\Http\Controllers;

use App\Models\Resena;
use App\Models\Comic;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ResenaController extends Controller
{
    /**
     * Almacena una reseña recién creada en la base de datos.
     */
    public function store(Request $request)
    {
        $request->validate([
            'id_usuario' => 'required|exists:usuarios,id_usuario',
            'id_comic' => 'required|exists:comics,id_comic',
            'valoracion' => 'required|integer|min:1|max:5',
            'comentario' => 'required|string',
        ]);

        // Verificar si el usuario ya ha reseñado este comic
        $existente = Resena::where('id_usuario', $request->id_usuario)
                          ->where('id_comic', $request->id_comic)
                          ->first();

        if ($existente) {
            return redirect()->back()
                ->with('error', 'Ya has publicado una reseña para este comic.')
                ->withInput();
        }

        Resena::create([
            'id_usuario' => $request->id_usuario,
            'id_comic' => $request->id_comic,
            'valoracion' => $request->valoracion,
            'comentario' => $request->comentario,
            'fecha' => now(),
        ]);

        return redirect()->back()
            ->with('success', 'Reseña publicada exitosamente');
    }

    /**
     * Almacena una reseña desde la vista de lectura.
     */
    public function storeFromReader(Request $request, $comic_id)
    {
        $request->validate([
            'valoracion' => 'required|integer|min:1|max:5',
            'comentario' => 'required|string|max:1000',
        ]);

        $usuario = Auth::user();
        
        // Verificar si el usuario ya ha reseñado este comic
        $existente = Resena::where('id_usuario', $usuario->id_usuario)
                          ->where('id_comic', $comic_id)
                          ->first();

        if ($existente) {
            return redirect()->back()
                ->with('error', 'Ya has publicado una reseña para este cómic.')
                ->withInput();
        }

        // Verificar que el cómic existe
        $comic = Comic::findOrFail($comic_id);

        Resena::create([
            'id_usuario' => $usuario->id_usuario,
            'id_comic' => $comic_id,
            'valoracion' => $request->valoracion,
            'comentario' => $request->comentario,
            'fecha' => now(),
        ]);

        return redirect()->back()
            ->with('success', 'Reseña publicada exitosamente');
    }
}
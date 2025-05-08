<?php

namespace App\Http\Controllers;

use App\Models\Resena;
use App\Models\Comic;
use App\Models\Usuario;
use Illuminate\Http\Request;

class ResenaController extends Controller
{
    /**
     * Muestra una lista de todas las reseñas.
     */
    public function index()
    {
        $resenas = Resena::with(['usuario', 'comic'])->latest('fecha')->get();
        return view('resenas.index', compact('resenas'));
    }

    /**
     * Muestra el formulario para crear una nueva reseña.
     */
    public function create()
    {
        $usuarios = Usuario::all();
        $comics = Comic::all();
        return view('resenas.create', compact('usuarios', 'comics'));
    }

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

        return redirect()->route('resenas.index')
            ->with('success', 'Reseña publicada exitosamente');
    }

    /**
     * Muestra la reseña especificada.
     */
    public function show(Resena $resena)
    {
        $resena->load(['usuario', 'comic']);
        return view('resenas.show', compact('resena'));
    }

    /**
     * Muestra el formulario para editar la reseña especificada.
     */
    public function edit(Resena $resena)
    {
        return view('resenas.edit', compact('resena'));
    }

    /**
     * Actualiza la reseña especificada en la base de datos.
     */
    public function update(Request $request, Resena $resena)
    {
        $request->validate([
            'valoracion' => 'required|integer|min:1|max:5',
            'comentario' => 'required|string',
        ]);

        $resena->update([
            'valoracion' => $request->valoracion,
            'comentario' => $request->comentario,
            'fecha' => now(), // Actualizar la fecha al editar
        ]);

        return redirect()->route('resenas.index')
            ->with('success', 'Reseña actualizada exitosamente');
    }

    /**
     * Elimina la reseña especificada de la base de datos.
     */
    public function destroy(Resena $resena)
    {
        $resena->delete();

        return redirect()->route('resenas.index')
            ->with('success', 'Reseña eliminada exitosamente');
    }

    /**
     * Muestra las reseñas de un comic específico.
     */
    public function porComic($id_comic)
    {
        $comic = Comic::findOrFail($id_comic);
        $resenas = Resena::where('id_comic', $id_comic)
                        ->with('usuario')
                        ->latest('fecha')
                        ->get();
        
        return view('resenas.por_comic', compact('comic', 'resenas'));
    }

    /**
     * Muestra las reseñas de un usuario específico.
     */
    public function porUsuario($id_usuario)
    {
        $usuario = Usuario::findOrFail($id_usuario);
        $resenas = Resena::where('id_usuario', $id_usuario)
                        ->with('comic')
                        ->latest('fecha')
                        ->get();
        
        return view('resenas.por_usuario', compact('usuario', 'resenas'));
    }
}
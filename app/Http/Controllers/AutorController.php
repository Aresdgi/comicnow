<?php

namespace App\Http\Controllers;

use App\Models\Autor;
use App\Models\Comic;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class AutorController extends Controller
{
    /**
     * Muestra una lista de todos los autores.
     */
    public function index()
    {
        $autores = Autor::withCount('comics')->get();
        return view('autores.index', compact('autores'));
    }

    /**
     * Muestra el formulario para crear un nuevo autor.
     */
    public function create()
    {
        return view('autores.create');
    }

    /**
     * Almacena un autor recién creado en la base de datos.
     */
    public function store(Request $request)
    {
        $request->validate([
            'nombre' => 'required|string|max:255',
            'biografia' => 'required|string',
            'foto_url' => 'nullable|image|max:2048',
            'nacionalidad' => 'nullable|string|max:100',
        ]);

        $data = $request->all();

        // Manejo de la imagen del autor
        if ($request->hasFile('foto_url')) {
            $fotoPath = $request->file('foto_url')->store('autores', 'public');
            $data['foto_url'] = $fotoPath;
        }

        Autor::create($data);

        return redirect()->route('autores.index')
            ->with('success', 'Autor creado exitosamente');
    }

    /**
     * Muestra el autor especificado.
     */
    public function show(Autor $autor)
    {
        $comics = Comic::where('id_autor', $autor->id_autor)->get();
        return view('autores.show', compact('autor', 'comics'));
    }

    /**
     * Muestra el formulario para editar el autor especificado.
     */
    public function edit(Autor $autor)
    {
        return view('autores.edit', compact('autor'));
    }

    /**
     * Actualiza el autor especificado en la base de datos.
     */
    public function update(Request $request, Autor $autor)
    {
        $request->validate([
            'nombre' => 'required|string|max:255',
            'biografia' => 'required|string',
            'foto_url' => 'nullable|image|max:2048',
            'nacionalidad' => 'nullable|string|max:100',
        ]);

        $data = $request->all();

        // Manejo de la imagen del autor
        if ($request->hasFile('foto_url')) {
            // Eliminar imagen anterior si existe
            if ($autor->foto_url && Storage::disk('public')->exists($autor->foto_url)) {
                Storage::disk('public')->delete($autor->foto_url);
            }
            $fotoPath = $request->file('foto_url')->store('autores', 'public');
            $data['foto_url'] = $fotoPath;
        }

        $autor->update($data);

        return redirect()->route('autores.index')
            ->with('success', 'Autor actualizado exitosamente');
    }

    /**
     * Elimina el autor especificado de la base de datos.
     */
    public function destroy(Autor $autor)
    {
        // Verificar si tiene comics asociados
        $comicsCount = Comic::where('id_autor', $autor->id_autor)->count();
        
        if ($comicsCount > 0) {
            return back()->with('error', 'No se puede eliminar el autor porque tiene comics asociados');
        }
        
        // Eliminar la imagen si existe
        if ($autor->foto_url && Storage::disk('public')->exists($autor->foto_url)) {
            Storage::disk('public')->delete($autor->foto_url);
        }
        
        $autor->delete();

        return redirect()->route('autores.index')
            ->with('success', 'Autor eliminado exitosamente');
    }
}
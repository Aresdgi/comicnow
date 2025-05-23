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
        $autores = Autor::withCount('comics')->orderBy('nombre')->paginate(10);
        return view('admin.autores.index', compact('autores'));
    }

    /**
     * Muestra el formulario para crear un nuevo autor.
     */
    public function create()
    {
        return view('admin.autores.create');
    }

    /**
     * Almacena un autor recién creado en la base de datos.
     */
    public function store(Request $request)
    {
        $request->validate([
            'nombre' => 'required|string|max:255',
            'biografia' => 'nullable|string',
            'editorial' => 'required|string|max:255',
            'comision' => 'required|numeric|min:0|max:100',
        ]);

        $data = $request->all();

        Autor::create($data);

        return redirect()->route('admin.autores.index')
            ->with('success', 'Autor creado exitosamente');
    }

    /**
     * Muestra el autor especificado.
     */
    public function show($autor)
    {
        $autor = Autor::findOrFail($autor);
        $comics = Comic::where('id_autor', $autor->id_autor)->paginate(6);
        return view('admin.autores.show', compact('autor', 'comics'));
    }

    /**
     * Muestra el formulario para editar el autor especificado.
     */
    public function edit($autor)
    {
        $autor = Autor::findOrFail($autor);
        return view('admin.autores.edit', compact('autor'));
    }

    /**
     * Actualiza el autor especificado en la base de datos.
     */
    public function update(Request $request, $autor)
    {
        $autor = Autor::findOrFail($autor);
        
        $request->validate([
            'nombre' => 'required|string|max:255',
            'biografia' => 'nullable|string',
            'editorial' => 'required|string|max:255',
            'comision' => 'required|numeric|min:0|max:100',
        ]);

        $data = $request->all();

        $autor->update($data);

        return redirect()->route('admin.autores.index')
            ->with('success', 'Autor actualizado exitosamente');
    }

    /**
     * Elimina el autor especificado de la base de datos.
     */
    public function destroy($autor)
    {
        $autor = Autor::findOrFail($autor);
        
        // Verificar si tiene comics asociados
        $comicsCount = Comic::where('id_autor', $autor->id_autor)->count();
        
        if ($comicsCount > 0) {
            return back()->with('error', 'No se puede eliminar el autor porque tiene comics asociados');
        }
        
        $autor->delete();

        return redirect()->route('admin.autores.index')
            ->with('success', 'Autor eliminado exitosamente');
    }
}
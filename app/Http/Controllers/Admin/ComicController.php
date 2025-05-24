<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Comic;
use App\Models\Autor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;

class ComicController extends Controller
{
    /**
     * Muestra una lista de todos los comics en el panel de administración.
     */
    public function index()
    {
        $comics = Comic::with('autor')->get();
        return view('admin.comics.index', compact('comics'));
    }

    /**
     * Muestra el formulario para crear un nuevo comic.
     */
    public function create()
    {
        $autores = Autor::all();
        return view('admin.comics.create', compact('autores'));
    }

    /**
     * Almacena un comic recién creado en la base de datos.
     */
    public function store(Request $request)
    {
        $request->validate([
            'titulo' => 'required|string|max:255',
            'id_autor' => 'required|exists:autores,id_autor',
            'descripcion' => 'required|string',
            'precio' => 'required|numeric|min:0',
            'categoria' => 'required|string|max:100',
            'portada_url' => 'nullable|image|max:2048',
            'archivo_comic' => 'nullable|file|mimes:pdf,cbz,cbr|max:20480', // 20MB max
        ]);

        $data = $request->all();

        // Manejar la imagen de portada
        if ($request->hasFile('portada_url')) {
            $portadaPath = $request->file('portada_url')->store('portadas', 'public');
            $data['portada_url'] = $portadaPath;
        }

        // Manejar el archivo del comic
        if ($request->hasFile('archivo_comic')) {
            $archivoPath = $request->file('archivo_comic')->store('comics', 'public');
            $data['archivo_comic'] = $archivoPath;
        }

        Comic::create($data);

        return redirect()->route('admin.comics.index')
            ->with('success', 'Comic creado exitosamente');
    }

    /**
     * Muestra el comic especificado.
     */
    public function show(Comic $comic)
    {
        $comic->load('autor');
        return view('admin.comics.show', compact('comic'));
    }

    /**
     * Muestra el formulario para editar el comic especificado.
     */
    public function edit(Comic $comic)
    {
        $autores = Autor::all();
        return view('admin.comics.edit', compact('comic', 'autores'));
    }

    /**
     * Actualiza el comic especificado en la base de datos.
     */
    public function update(Request $request, Comic $comic)
    {
        $request->validate([
            'titulo' => 'required|string|max:255',
            'id_autor' => 'required|exists:autores,id_autor',
            'descripcion' => 'required|string',
            'precio' => 'required|numeric|min:0',
            'categoria' => 'required|string|max:100',
            'portada_url' => 'nullable|image|max:2048',
            'archivo_comic' => 'nullable|file|mimes:pdf,cbz,cbr|max:20480', // 20MB max
        ]);

        // Solo tomar los campos que siempre se actualizan
        $data = $request->only(['titulo', 'id_autor', 'descripcion', 'precio', 'categoria']);

        // Manejar la imagen de portada SOLO si se sube una nueva
        if ($request->hasFile('portada_url')) {
            Log::info('Editando portada: archivo recibido');
            Log::info('Nombre original del archivo: ' . $request->file('portada_url')->getClientOriginalName());
            Log::info('Tamaño del archivo: ' . $request->file('portada_url')->getSize() . ' bytes');
            
            // Eliminar la portada anterior si existe
            if ($comic->portada_url && Storage::disk('public')->exists($comic->portada_url)) {
                Storage::disk('public')->delete($comic->portada_url);
                Log::info('Portada anterior eliminada: ' . $comic->portada_url);
            }
            
            try {
                $portadaPath = $request->file('portada_url')->store('portadas', 'public');
                if ($portadaPath) {
                    $data['portada_url'] = $portadaPath;
                    Log::info('Nueva portada guardada: ' . $portadaPath);
                } else {
                    Log::error('Error: store() devolvió false o null');
                }
            } catch (\Exception $e) {
                Log::error('Excepción al guardar portada: ' . $e->getMessage());
                return back()->with('error', 'Error al guardar la imagen de portada');
            }
        } else {
            Log::info('Editando cómic sin nueva portada, manteniendo: ' . $comic->portada_url);
        }

        // Manejar el archivo del comic SOLO si se sube uno nuevo
        if ($request->hasFile('archivo_comic')) {
            // Eliminar el archivo anterior si existe
            if ($comic->archivo_comic && Storage::disk('public')->exists($comic->archivo_comic)) {
                Storage::disk('public')->delete($comic->archivo_comic);
            }
            $archivoPath = $request->file('archivo_comic')->store('comics', 'public');
            $data['archivo_comic'] = $archivoPath;
        }

        $comic->update($data);

        return redirect()->route('admin.comics.index')
            ->with('success', 'Comic actualizado exitosamente');
    }

    /**
     * Elimina el comic especificado de la base de datos.
     */
    public function destroy(Comic $comic)
    {
        // Comprobar si hay reseñas, pedidos o biblioteca relacionados antes de eliminar
        if ($comic->resenas()->count() > 0 || $comic->detallePedidos()->count() > 0 || $comic->bibliotecas()->count() > 0) {
            return back()->with('error', 'No se puede eliminar el comic porque tiene reseñas, pedidos o está en bibliotecas de usuarios');
        }
        
        // Eliminar archivos asociados
        if ($comic->portada_url && Storage::disk('public')->exists($comic->portada_url)) {
            Storage::disk('public')->delete($comic->portada_url);
        }
        
        if ($comic->archivo_comic && Storage::disk('public')->exists($comic->archivo_comic)) {
            Storage::disk('public')->delete($comic->archivo_comic);
        }
        
        $comic->delete();

        return redirect()->route('admin.comics.index')
            ->with('success', 'Comic eliminado exitosamente');
    }
}
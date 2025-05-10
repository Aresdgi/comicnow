<?php

namespace App\Http\Controllers;

use App\Models\Comic;
use App\Models\Autor;
use App\Models\Resena;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ComicController extends Controller
{
    /**
     * Muestra una lista de todos los comics.
     */
    public function index()
    {
        $comics = Comic::with('autor')->get();
        return view('comics.index', compact('comics'));
    }

    /**
     * Muestra el formulario para crear un nuevo comic.
     */
    public function create()
    {
        $autores = Autor::all();
        return view('comics.create', compact('autores'));
    }

    /**
     * Almacena un comic recién creado en la base de datos.
     */
    public function store(Request $request)
    {
        $request->validate([
            'titulo' => 'required|string|max:255',
            'id_autor' => 'required|exists:autores,id_autor',
            'año_publicacion' => 'required|integer|min:1900|max:' . date('Y'),
            'descripcion' => 'required|string',
            'genero' => 'required|string|max:100',
            'precio' => 'required|numeric|min:0',
            'stock' => 'required|integer|min:0',
            'portada_url' => 'nullable|image|max:2048',
            'archivo_url' => 'nullable|file|mimes:pdf,cbz,cbr|max:20480', // 20MB max
        ]);

        $data = $request->all();

        // Manejar la imagen de portada
        if ($request->hasFile('portada_url')) {
            $portadaPath = $request->file('portada_url')->store('portadas', 'public');
            $data['portada_url'] = $portadaPath;
        }

        // Manejar el archivo del comic
        if ($request->hasFile('archivo_url')) {
            $archivoPath = $request->file('archivo_url')->store('comics', 'public');
            $data['archivo_url'] = $archivoPath;
        }

        Comic::create($data);

        return redirect()->route('comics.index')
            ->with('success', 'Comic creado exitosamente');
    }

    /**
     * Muestra el comic especificado.
     */
    public function show(Comic $comic)
    {
        $comic->load('autor');
        $resenas = Resena::where('id_comic', $comic->id_comic)
                       ->with('usuario')
                       ->latest('fecha')
                       ->take(10)
                       ->get();
        
        $valoracionPromedio = $resenas->avg('valoracion');
        
        return view('comics.show', compact('comic', 'resenas', 'valoracionPromedio'));
    }

    /**
     * Muestra el formulario para editar el comic especificado.
     */
    public function edit(Comic $comic)
    {
        $autores = Autor::all();
        return view('comics.edit', compact('comic', 'autores'));
    }

    /**
     * Actualiza el comic especificado en la base de datos.
     */
    public function update(Request $request, Comic $comic)
    {
        $request->validate([
            'titulo' => 'required|string|max:255',
            'id_autor' => 'required|exists:autores,id_autor',
            'año_publicacion' => 'required|integer|min:1900|max:' . date('Y'),
            'descripcion' => 'required|string',
            'genero' => 'required|string|max:100',
            'precio' => 'required|numeric|min:0',
            'stock' => 'required|integer|min:0',
            'portada_url' => 'nullable|image|max:2048',
            'archivo_url' => 'nullable|file|mimes:pdf,cbz,cbr|max:20480', // 20MB max
        ]);

        $data = $request->all();

        // Manejar la imagen de portada
        if ($request->hasFile('portada_url')) {
            // Eliminar la portada anterior si existe
            if ($comic->portada_url && Storage::disk('public')->exists($comic->portada_url)) {
                Storage::disk('public')->delete($comic->portada_url);
            }
            $portadaPath = $request->file('portada_url')->store('portadas', 'public');
            $data['portada_url'] = $portadaPath;
        }

        // Manejar el archivo del comic
        if ($request->hasFile('archivo_url')) {
            // Eliminar el archivo anterior si existe
            if ($comic->archivo_url && Storage::disk('public')->exists($comic->archivo_url)) {
                Storage::disk('public')->delete($comic->archivo_url);
            }
            $archivoPath = $request->file('archivo_url')->store('comics', 'public');
            $data['archivo_url'] = $archivoPath;
        }

        $comic->update($data);

        return redirect()->route('comics.index')
            ->with('success', 'Comic actualizado exitosamente');
    }

    /**
     * Elimina el comic especificado de la base de datos.
     */
    public function destroy(Comic $comic)
    {
        // Comprobar si hay reseñas, pedidos o biblioteca relacionados antes de eliminar
        if ($comic->resenas()->count() > 0 || $comic->detallePedidos()->count() > 0 || $comic->biblioteca()->count() > 0) {
            return back()->with('error', 'No se puede eliminar el comic porque tiene reseñas, pedidos o está en bibliotecas de usuarios');
        }
        
        // Eliminar archivos asociados
        if ($comic->portada_url && Storage::disk('public')->exists($comic->portada_url)) {
            Storage::disk('public')->delete($comic->portada_url);
        }
        
        if ($comic->archivo_url && Storage::disk('public')->exists($comic->archivo_url)) {
            Storage::disk('public')->delete($comic->archivo_url);
        }
        
        $comic->delete();

        return redirect()->route('comics.index')
            ->with('success', 'Comic eliminado exitosamente');
    }
    
    /**
     * Muestra los comics destacados en la página principal.
     */
    public function destacados()
    {
        $masVendidos = Comic::withCount('detallePedidos')
                         ->orderByDesc('detalle_pedidos_count')
                         ->take(6)
                         ->get();
                         
        $mejorValorados = Comic::withCount('resenas')
                           ->whereHas('resenas', function($query) {
                               $query->where('valoracion', '>=', 4);
                           })
                           ->orderByDesc('resenas_count')
                           ->take(6)
                           ->get();
                           
        $ultimosLanzamientos = Comic::orderByDesc('created_at')
                              ->take(6)
                              ->get();
        
        return view('home', compact('masVendidos', 'mejorValorados', 'ultimosLanzamientos'));
    }
    
    /**
     * Buscar comics por diferentes criterios.
     */
    public function buscar(Request $request)
    {
        $query = Comic::query()->with('autor');
        
        if ($request->filled('titulo')) {
            $query->where('titulo', 'like', '%' . $request->titulo . '%');
        }
        
        if ($request->filled('autor')) {
            $query->whereHas('autor', function($q) use ($request) {
                $q->where('nombre', 'like', '%' . $request->autor . '%');
            });
        }
        
        if ($request->filled('genero')) {
            $query->where('genero', 'like', '%' . $request->genero . '%');
        }
        
        if ($request->filled('año_desde')) {
            $query->where('año_publicacion', '>=', $request->año_desde);
        }
        
        if ($request->filled('año_hasta')) {
            $query->where('año_publicacion', '<=', $request->año_hasta);
        }
        
        $comics = $query->orderBy('titulo')->paginate(12);
        
        return view('comics.busqueda', compact('comics', 'request'));
    }
}
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
    public function index(Request $request)
    {
        $query = Comic::with('autor');
        
        // Si hay búsqueda, filtrar por título o autor
        if ($request->filled('buscar')) {
            $buscar = $request->get('buscar');
            $query->where(function($q) use ($buscar) {
                $q->where('titulo', 'like', '%' . $buscar . '%')
                  ->orWhereHas('autor', function($autorQuery) use ($buscar) {
                      $autorQuery->where('nombre', 'like', '%' . $buscar . '%');
                  });
            });
        }
        
        $comics = $query->orderBy('titulo')->get();
        return view('comics.index', compact('comics'));
    }



    /**
     * Muestra el comic especificado.
     */
    public function show(Comic $comic)
    {
        // Cargar reseñas del comic
        $comic->load('resenas.usuario');
        
        // Calcular promedio de calificaciones
        $calificacionPromedio = $comic->resenas->avg('valoracion');
        
        return view('comics.show', compact('comic', 'calificacionPromedio'));
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
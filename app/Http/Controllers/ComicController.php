<?php

namespace App\Http\Controllers;

use App\Models\Comic;
use Illuminate\Http\Request;

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


    

}
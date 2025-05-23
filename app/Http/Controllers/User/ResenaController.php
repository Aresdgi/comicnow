<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Resena;

class ResenaController extends Controller
{
    /**
     * Mostrar las reseñas del usuario
     */
    public function index()
    {
        // Obtener todas las reseñas del usuario actual
        $resenas = Resena::where('id_usuario', Auth::user()->id_usuario)
            ->with(['comic.autor'])
            ->orderBy('created_at', 'desc')
            ->get();
            
        return view('user.resenas', compact('resenas'));
    }
    
    /**
     * Editar una reseña
     */
    public function edit($id_resena)
    {
        $resena = Resena::where('id_usuario', Auth::user()->id_usuario)
            ->where('id_resena', $id_resena)
            ->firstOrFail();
            
        return view('user.resenas-edit', compact('resena'));
    }
    
    /**
     * Actualizar una reseña
     */
    public function update(Request $request, $id_resena)
    {
        $resena = Resena::where('id_usuario', Auth::user()->id_usuario)
            ->where('id_resena', $id_resena)
            ->firstOrFail();
            
        $resena->update([
            'comentario' => $request->comentario,
            'valoracion' => $request->valoracion,
        ]);
        
        return redirect()->route('user.resenas')->with('success', 'Reseña actualizada correctamente');
    }
    
    /**
     * Eliminar una reseña
     */
    public function destroy($id_resena)
    {
        $resena = Resena::where('id_usuario', Auth::user()->id_usuario)
            ->where('id_resena', $id_resena)
            ->firstOrFail();
            
        $resena->delete();
        
        return back()->with('success', 'Reseña eliminada correctamente');
    }
}
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
        $resenas = Resena::where('id_usuario', Auth::id())
            ->with('comic')
            ->orderBy('created_at', 'desc')
            ->get();
            
        return view('user.resenas', compact('resenas'));
    }
    
    /**
     * Editar una reseña
     */
    public function edit($id)
    {
        $resena = Resena::where('id_usuario', Auth::id())
            ->where('id', $id)
            ->firstOrFail();
            
        return view('user.resenas-edit', compact('resena'));
    }
    
    /**
     * Actualizar una reseña
     */
    public function update(Request $request, $id)
    {
        $resena = Resena::where('id_usuario', Auth::id())
            ->where('id', $id)
            ->firstOrFail();
            
        $resena->update([
            'comentario' => $request->comentario,
            'puntuacion' => $request->puntuacion,
        ]);
        
        return redirect()->route('user.resenas')->with('success', 'Reseña actualizada correctamente');
    }
    
    /**
     * Eliminar una reseña
     */
    public function destroy($id)
    {
        $resena = Resena::where('id_usuario', Auth::id())
            ->where('id', $id)
            ->firstOrFail();
            
        $resena->delete();
        
        return back()->with('success', 'Reseña eliminada correctamente');
    }
}
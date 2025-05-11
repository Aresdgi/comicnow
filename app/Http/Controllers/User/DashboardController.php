<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Biblioteca;
use App\Models\Comic;
use App\Models\Pedido;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Exception;
use Illuminate\Support\Facades\Log;

class DashboardController extends Controller
{
    /**
     * Muestra el dashboard principal del usuario
     */
    public function index()
    {
        try {
            // Obtener el usuario actual
            $usuario = Auth::user();
            
            if (!$usuario) {
                return redirect()->route('login');
            }
            
            // Para debugging
            Log::info('Usuario ID: ' . $usuario->id);
            Log::info('Usuario actual: ', ['usuario' => $usuario->toArray()]);
            
            // Inicializar variables
            $comicsRecientes = collect();
            $pedidosRecientes = collect();
            $recomendaciones = collect();
            
            // Intentar obtener los cómics más recientes del usuario (últimos 6)
            try {
                $comicsRecientes = Biblioteca::where('id_usuario', $usuario->id)
                    ->with(['comic', 'comic.autor'])
                    ->orderBy('updated_at', 'desc')
                    ->take(6)
                    ->get();
                Log::info('Comics recientes obtenidos: ' . $comicsRecientes->count());
            } catch (Exception $e) {
                Log::error('Error al obtener cómics recientes: ' . $e->getMessage());
            }
            
            // Intentar obtener los pedidos recientes del usuario (últimos 5)
            try {
                $pedidosRecientes = Pedido::where('id_usuario', $usuario->id)
                    ->with('detalles')
                    ->orderBy('fecha', 'desc')
                    ->take(5)
                    ->get();
                Log::info('Pedidos recientes obtenidos: ' . $pedidosRecientes->count());
                
                // Calcular el total de cada pedido sumando los detalles
                foreach ($pedidosRecientes as $pedido) {
                    $pedido->total = $pedido->detalles ? $pedido->detalles->sum(function ($detalle) {
                        return $detalle->precio * $detalle->cantidad;
                    }) : 0;
                }
            } catch (Exception $e) {
                Log::error('Error al obtener pedidos recientes: ' . $e->getMessage());
            }
            
            // Intentar obtener recomendaciones
            try {
                $recomendaciones = $this->getRecomendaciones($usuario->id);
                Log::info('Recomendaciones obtenidas: ' . $recomendaciones->count());
            } catch (Exception $e) {
                Log::error('Error al obtener recomendaciones: ' . $e->getMessage());
                $recomendaciones = collect();
            }
            
            return view('user.dashboard', compact(
                'comicsRecientes',
                'pedidosRecientes',
                'recomendaciones'
            ));
            
        } catch (Exception $e) {
            Log::error('Error general en el dashboard: ' . $e->getMessage());
            return view('user.dashboard', [
                'comicsRecientes' => collect(),
                'pedidosRecientes' => collect(),
                'recomendaciones' => collect(),
                'error' => 'Hubo un problema al cargar tu dashboard.'
            ]);
        }
    }
    
    /**
     * Genera recomendaciones de cómics para el usuario basadas en sus compras y preferencias
     */
    private function getRecomendaciones($idUsuario)
    {
        try {
            // Obtener los IDs de los cómics que el usuario ya tiene en su biblioteca
            $comicsUsuario = Biblioteca::where('id_usuario', $idUsuario)
                ->pluck('id_comic')
                ->toArray();
                
            // Obtener los cómics más populares si no hay libros en la biblioteca
            if (empty($comicsUsuario)) {
                return Comic::with('autor')
                    ->orderBy('id_comic', 'desc') // Asumimos que los más nuevos tienen ID más alto
                    ->take(6)
                    ->get();
            }
                
            // Obtener los autores que le gustan al usuario basado en sus compras
            $autoresFavoritos = DB::table('pedidos')
                ->join('detalle_pedidos', 'pedidos.id_pedido', '=', 'detalle_pedidos.id_pedido')
                ->join('comics', 'detalle_pedidos.id_comic', '=', 'comics.id_comic')
                ->where('pedidos.id_usuario', $idUsuario)
                ->groupBy('comics.id_autor')
                ->select('comics.id_autor', DB::raw('COUNT(*) as total'))
                ->orderBy('total', 'desc')
                ->pluck('id_autor')
                ->toArray();
                
            // Si no hay autores favoritos, recomendar los cómics más populares
            if (empty($autoresFavoritos)) {
                return Comic::with('autor')
                    ->whereNotIn('id_comic', $comicsUsuario) // Excluir los que ya tiene
                    ->orderBy('id_comic', 'desc')
                    ->take(6)
                    ->get();
            }
            
            // Recomendar cómics de los autores favoritos que el usuario aún no tiene
            $recomendaciones = Comic::whereIn('id_autor', $autoresFavoritos)
                ->whereNotIn('id_comic', $comicsUsuario)
                ->with('autor')
                ->take(6)
                ->get();
                
            // Si no hay suficientes recomendaciones, agregar cómics populares
            if ($recomendaciones->count() < 6) {
                $faltantes = 6 - $recomendaciones->count();
                
                $popularesIds = Comic::whereNotIn('id_comic', $comicsUsuario)
                    ->whereNotIn('id_comic', $recomendaciones->pluck('id_comic')->toArray())
                    ->orderBy('id_comic', 'desc')
                    ->take($faltantes)
                    ->pluck('id_comic');
                    
                $comicsPopulares = Comic::whereIn('id_comic', $popularesIds)
                    ->with('autor')
                    ->get();
                    
                $recomendaciones = $recomendaciones->concat($comicsPopulares);
            }
            
            return $recomendaciones;
        } catch (Exception $e) {
            Log::error('Error en getRecomendaciones: ' . $e->getMessage());
            return collect();
        }
    }
}
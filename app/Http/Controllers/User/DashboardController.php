<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
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
            
            // Ya no necesitamos cargar cómics recientes, pedidos recientes ni recomendaciones
            // porque la vista simplificada no los mostrará
            
            return view('user.dashboard');
            
        } catch (Exception $e) {
            Log::error('Error general en el dashboard: ' . $e->getMessage());
            return view('user.dashboard', [
                'error' => 'Hubo un problema al cargar tu dashboard.'
            ]);
        }
    }
}
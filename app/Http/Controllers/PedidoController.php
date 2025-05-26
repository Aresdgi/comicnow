<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Pedido;
use Illuminate\Support\Facades\Auth;

class PedidoController extends Controller
{
    /**
     * Muestra una lista de los pedidos del usuario autenticado.
     */
    public function index()
    {
        $pedidos = Pedido::with(['detalles.comic'])
                         ->where('id_usuario', Auth::id())
                         ->orderByDesc('fecha')
                         ->get();
        return view('pedidos.index', compact('pedidos'));
    }

    /**
     * Muestra los detalles de un pedido específico.
     */
    public function show(Pedido $pedido)
    {
        // Verificar que el pedido pertenece al usuario autenticado
        if ($pedido->id_usuario !== Auth::id()) {
            abort(403, 'No tienes permiso para ver este pedido.');
        }

        // Cargar las relaciones necesarias
        $pedido->load(['detalles.comic']);

        return view('pedidos.show', compact('pedido'));
    }
}
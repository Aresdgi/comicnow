<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Pedido;
use Illuminate\Support\Facades\Auth;

class PedidoController extends Controller
{
    /**
     * Constructor para aplicar middleware de autenticación
     */
    public function __construct()
    {
        // La autenticación se implementará en las rutas
    }

    /**
     * Muestra una lista de los pedidos del usuario autenticado.
     */
    public function index()
    {
        $pedidos = Pedido::with(['detalles.comic', 'usuario'])
                         ->where('id_usuario', Auth::id())
                         ->orderByDesc('fecha')
                         ->get();
        return view('pedidos.index', compact('pedidos'));
    }

    /**
     * Muestra el pedido especificado.
     */
    public function show($id)
    {
        $pedido = Pedido::with(['detalles.comic', 'usuario'])
                ->where('id_usuario', Auth::id())
                ->findOrFail($id);
                
        return view('pedidos.show', compact('pedido'));
    }
}
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


}
<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Pedido;
use App\Models\DetallePedido;
use App\Models\Comic;
use App\Services\CashierService;
use Illuminate\Support\Facades\Auth;

class CashierController extends Controller
{
    /**
     * Muestra la página de checkout con Cashier
     */    public function checkout()
    {
        // Obtener el carrito del usuario
        $user = Auth::user();
        $carrito = session()->get('carrito', []);
        
        if (empty($carrito)) {
            return redirect()->route('carrito.index')->with('error', 'Tu carrito está vacío');
        }
        
        $items = [];
        $total = 0;
        
        // Preparar los items para Stripe
        foreach ($carrito as $id => $item) {
            $comic = Comic::find($id);
            if ($comic) {
                $total += $comic->precio * $item['cantidad'];
                $items[] = [
                    'comic' => $comic,
                    'cantidad' => $item['cantidad'],
                    'precio_unitario' => $comic->precio,
                    'subtotal' => $comic->precio * $item['cantidad']
                ];
            }
        }
        
        // Crear intención de pago para Stripe
        $intent = $user->createSetupIntent();
        
        return view('checkout.index', [
            'items' => $items,
            'total' => $total,
            'intent' => $intent
        ]);
    }
      /**
     * Procesa el pago con Stripe
     */
    public function process(Request $request)
    {
        $user = Auth::user();
        $carrito = session()->get('carrito', []);
        
        if (empty($carrito)) {
            return redirect()->route('carrito.index')->with('error', 'Tu carrito está vacío');
        }
        
        try {
            $paymentMethod = $request->input('payment_method');
            
            // Usar nuestro servicio para procesar el pedido completo
            $cashierService = new CashierService();
            $pedido = $cashierService->procesarPedido($user, $carrito, $paymentMethod);
            
            // Vaciar carrito
            session()->forget('carrito');
            
            return redirect()->route('pedidos.show', $pedido->id_pedido)
                ->with('success', 'Tu pedido ha sido procesado correctamente.');
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Ocurrió un error al procesar tu pago: ' . $e->getMessage());
        }
    }
      /**
     * Redirige al usuario a la página de éxito del pedido
     */
    public function success($id_pedido)
    {
        $pedido = Pedido::with('detalles')->findOrFail($id_pedido);
        
        // Verificar que el pedido pertenezca al usuario autenticado
        if ($pedido->id_usuario != Auth::id()) {
            return redirect()->route('pedidos.index')->with('error', 'No tienes acceso a este pedido.');
        }
        
        return view('checkout.success', compact('pedido'));
    }
}

<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Pedido;
use App\Models\DetallePedido;
use App\Models\Comic;
use App\Models\Biblioteca;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Stripe\Stripe;

class CashierController extends Controller
{
    /**
     * Muestra la página de checkout con Stripe Checkout
     */    
    public function checkout()
    {
        // Obtener el carrito del usuario
        $user = Auth::user();
        $carrito = session()->get('carrito', []);
        
        if (empty($carrito)) {
            return redirect()->route('carrito.index')->with('error', 'Tu carrito está vacío');
        }
        
        $line_items = [];
        $total = 0;
        
        // Preparar los items para Stripe Checkout
        foreach ($carrito as $id => $item) {
            $comic = Comic::find($id);
            if ($comic) {
                $total += $comic->precio * $item['cantidad'];
                
                // Formato para Stripe Checkout
                $line_items[] = [
                    'price_data' => [
                        'currency' => 'eur',
                        'product_data' => [
                            'name' => $comic->titulo,
                            'images' => $comic->imagen && !empty($comic->imagen) ? [url($comic->imagen)] : [],
                        ],
                        'unit_amount' => $comic->precio * 100, // En céntimos
                    ],
                    'quantity' => $item['cantidad'],
                ];
            }
        }
        
        // Configurar Stripe
        Stripe::setApiKey(config('cashier.secret'));
        
        // Crear sesión de Stripe Checkout
        $checkout_session = \Stripe\Checkout\Session::create([
            'payment_method_types' => ['card'],
            'line_items' => $line_items,
            'mode' => 'payment',
            'customer_email' => $user->email,
            'success_url' => route('checkout.success') . '?session_id={CHECKOUT_SESSION_ID}',
            'cancel_url' => route('carrito.index'),
            'metadata' => [
                'user_id' => $user->id
            ]
        ]);
        
        return view('checkout.redirect', [
            'checkout_url' => $checkout_session->url
        ]);
    }
      
    /**
     * Procesa el pago con Stripe
     */
    public function process(Request $request)
    {
        // Este método ya no es necesario con Stripe Checkout
        // La redirección a success o cancel se maneja automáticamente por Stripe
        return redirect()->route('carrito.index');
    }
      
    /**
     * Maneja el retorno exitoso de Stripe Checkout
     */
    public function success(Request $request)
    {
        $sessionId = $request->get('session_id');
        
        if (!$sessionId) {
            return redirect()->route('carrito.index')
                ->with('error', 'No se pudo procesar el pago. Sesión de checkout no encontrada.');
        }
        
        try {
            // Configurar Stripe
            Stripe::setApiKey(config('cashier.secret'));
            
            // Recuperar información de la sesión de Stripe
            $session = \Stripe\Checkout\Session::retrieve($sessionId);
            
            // Verificar que la sesión está pagada
            if ($session->payment_status !== 'paid') {
                return redirect()->route('carrito.index')
                    ->with('error', 'El pago no ha sido completado.');
            }
            
            $user = Auth::user();
            $carrito = session()->get('carrito', []);
            
            // Procesar el pedido en la base de datos
            $pedido = $this->procesarPedidoDesdeCheckout($user, $carrito, $session);
            
            // Vaciar carrito
            session()->forget('carrito');
            
            return view('checkout.success', compact('pedido'));
        } catch (\Exception $e) {
            return redirect()->route('carrito.index')
                ->with('error', 'Ocurrió un error al procesar tu pago: ' . $e->getMessage());
        }
    }

    /**
     * Procesar un pedido desde Stripe Checkout
     * 
     * @param \App\Models\User $user
     * @param array $carrito
     * @param \Stripe\Checkout\Session $session
     * @return Pedido
     */
    private function procesarPedidoDesdeCheckout($user, array $carrito, $session)
    {
        // Calcular el total
        $total = 0;
        foreach ($carrito as $id => $item) {
            $comic = Comic::find($id);
            if ($comic) {
                $total += $comic->precio * $item['cantidad'];
            }
        }
        
        // Crear el pedido
        $pedido = new Pedido();
        $pedido->id_usuario = $user->id_usuario;
        $pedido->fecha = now();
        $pedido->estado = 'pagado';
        $pedido->metodo_pago = 'stripe_checkout';
        $pedido->total = $total;
        $pedido->payment_id = $session->payment_intent;
        $pedido->save();
        
        // Crear los detalles del pedido
        foreach ($carrito as $id => $item) {
            $comic = Comic::find($id);
            
            if ($comic) {
                $detalle = new DetallePedido();
                $detalle->id_pedido = $pedido->id_pedido; 
                $detalle->id_comic = $id;
                $detalle->cantidad = $item['cantidad'];
                $detalle->precio = $comic->precio;
                $detalle->save();
                
                // Verificar si el cómic ya existe en la biblioteca del usuario
                $existeEnBiblioteca = Biblioteca::where('id_usuario', $user->id_usuario)
                                           ->where('id_comic', $id)
                                           ->exists();
                
                // Solo añadir a biblioteca si no existe
                if (!$existeEnBiblioteca) {
                    $biblioteca = new Biblioteca();
                    $biblioteca->id_usuario = $user->id_usuario;
                    $biblioteca->id_comic = $id;
                    $biblioteca->progreso_lectura = 0;
                    $biblioteca->ultimo_marcador = 0;
                    $biblioteca->save();
                } else {
                    Log::info('Cómic ya existente en biblioteca', [
                        'user_id' => $user->id_usuario,
                        'comic_id' => $id
                    ]);
                }
            }
        }
        
        Log::info('Pedido procesado desde Stripe Checkout', [
            'user_id' => $user->id_usuario,
            'total' => $total,
            'session_id' => $session->id
        ]);
        
        return $pedido;
    }
}

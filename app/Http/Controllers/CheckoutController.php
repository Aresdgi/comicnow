<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\StripeService;
use App\Models\Pedido;
use App\Models\DetallePedido;
use App\Models\Comic;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class CheckoutController extends Controller
{
    protected $stripeService;    public function __construct(StripeService $stripeService)
    {
        $this->stripeService = $stripeService;
        // No necesitamos middleware aquí ya que está definido en las rutas
    }

    /**
     * Iniciar el proceso de checkout
     *
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function checkout(Request $request)
    {
        // Validar el carrito
        if (!session()->has('carrito') || empty(session('carrito'))) {
            return redirect()->route('carrito.index')->with('error', 'Tu carrito está vacío');
        }

        try {
            DB::beginTransaction();            // Crear el pedido
            $pedido = new Pedido();
            $pedido->id_usuario = auth()->user()->id_usuario;
            $pedido->fecha = now();
            $pedido->estado = 'pendiente';
            $pedido->metodo_pago = 'stripe';
            $pedido->save();            // Crear los detalles del pedido
            foreach (session('carrito') as $comicId => $item) {
                $comic = Comic::findOrFail($comicId);
                  $detalle = new DetallePedido();
                $detalle->id_pedido = $pedido->id_pedido;
                $detalle->id_comic = $comicId;
                $detalle->cantidad = $item['cantidad'];
                $detalle->precio = $comic->precio;
                $detalle->save();
            }

            DB::commit();

            // Crear la sesión de checkout de Stripe
            $checkoutUrl = $this->stripeService->createCheckoutSession($pedido);

            // Limpiar el carrito
            session()->forget('carrito');

            // Redireccionar a Stripe
            return redirect($checkoutUrl);

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error en el checkout: ' . $e->getMessage());
            return redirect()->route('carrito.index')->with('error', 'Ha ocurrido un error al procesar tu pago. Por favor, inténtalo de nuevo.');
        }
    }

    /**
     * Manejar el éxito del pago
     *
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */    public function success(Request $request)
    {
        $sessionId = $request->get('session_id');
        
        // Verificar si session_id es el placeholder y no un ID real
        if ($sessionId === '{CHECKOUT_SESSION_ID}') {
            Log::warning('Recibido placeholder {CHECKOUT_SESSION_ID} en lugar de un ID de sesión real');
            return redirect()->route('pedidos.index')
                ->with('warning', 'No se pudo verificar el estado del pago. Por favor, contacta con atención al cliente.');
        }

        try {
            $payment = $this->stripeService->checkSessionStatus($sessionId);
            
            if ($payment['status'] === 'succeeded') {                $pedido = Pedido::findOrFail($payment['pedido_id']);
                
                if ($pedido->estado !== 'pagado') {
                    $pedido->estado = 'pagado';
                    $pedido->save();
                    
                    // Registrar la transacción exitosa
                    \App\Models\StripeTransaction::create([
                        'pedido_id' => $pedido->id_pedido,
                        'usuario_id' => auth()->user()->id_usuario,
                        'stripe_payment_id' => $payment['payment_intent'] ?? null,
                        'stripe_session_id' => $sessionId,
                        'monto' => $payment['amount'],
                        'moneda' => $payment['currency'],
                        'estado' => $payment['status'],
                        'meta_datos' => json_encode($payment),
                        'fecha_pago' => now(),
                    ]);                    // Añadir los cómics comprados a la biblioteca del usuario
                    foreach ($pedido->detalles as $detalle) {
                        $biblioteca = \App\Models\Biblioteca::firstOrNew([
                            'id_usuario' => auth()->user()->id_usuario,
                            'id_comic' => $detalle->id_comic,
                        ]);
                        
                        if (!$biblioteca->exists) {
                            $biblioteca->fecha_adquisicion = now();
                            $biblioteca->save();
                        }
                    }
                }

                return redirect()->route('pedidos.show', $pedido->id)
                    ->with('success', '¡Pago exitoso! Tu pedido ha sido procesado correctamente.');
            } else {
                return redirect()->route('pedidos.index')
                    ->with('warning', 'El pago está en proceso. Te notificaremos cuando se complete.');
            }
        } catch (\Exception $e) {
            Log::error('Error al verificar el pago: ' . $e->getMessage());
            return redirect()->route('pedidos.index')
                ->with('error', 'Ha ocurrido un error al verificar tu pago. Por favor, contacta con atención al cliente.');
        }
    }

    /**
     * Manejar la cancelación del pago
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function cancel()
    {
        return redirect()->route('carrito.index')
            ->with('warning', 'Has cancelado el proceso de pago. Tu carrito sigue disponible.');
    }    /**
     * Manejar el webhook de Stripe
     *
     * @param Request $request
     * @return \Illuminate\Http\Response
     */
    public function webhook(Request $request)
    {
        $payload = $request->getContent();
        $sigHeader = $request->header('Stripe-Signature');
        
        try {
            $event = null;
            $endpointSecret = config('services.stripe.webhook_secret');

            try {
                $event = \Stripe\Webhook::constructEvent(
                    $payload,
                    $sigHeader,
                    $endpointSecret
                );
            } catch (\UnexpectedValueException $e) {
                return response(['error' => 'Invalid payload'], 400);
            } catch (\Stripe\Exception\SignatureVerificationException $e) {
                return response(['error' => 'Invalid signature'], 400);
            }

            // Manejar el evento
            if ($event->type === 'checkout.session.completed') {
                $session = $event->data->object;
                  Log::info('Webhook de Stripe recibido: checkout.session.completed', [
                    'session_id' => $session->id,
                    'payment_intent' => $session->payment_intent,
                    'metadata' => $session->metadata
                ]);
                
                // Obtener el ID del pedido
                $pedidoId = $session->metadata->pedido_id ?? null;
                
                if ($pedidoId) {
                    $pedido = Pedido::find($pedidoId);
                    
                    if ($pedido && $pedido->estado !== 'pagado') {
                        // Obtener los detalles del pago
                        $payment = $this->stripeService->stripeClient->paymentIntents->retrieve($session->payment_intent);
                          // Actualizar el estado del pedido
                        $pedido->estado = 'pagado';
                        $pedido->save();
                        
                        // Registrar la transacción
                        \App\Models\StripeTransaction::create([
                            'pedido_id' => $pedido->id_pedido,
                            'usuario_id' => $pedido->id_usuario,
                            'stripe_payment_id' => $session->payment_intent,
                            'stripe_session_id' => $session->id,
                            'monto' => $payment->amount / 100, // Convertir de centavos a unidades
                            'moneda' => $payment->currency,
                            'estado' => $payment->status,
                            'meta_datos' => json_encode([
                                'session' => $session,
                                'payment' => $payment,
                            ]),
                            'fecha_pago' => now(),
                        ]);
                          // Añadir los cómics comprados a la biblioteca del usuario
                        foreach ($pedido->detalles as $detalle) {
                            $biblioteca = \App\Models\Biblioteca::firstOrNew([
                                'id_usuario' => $pedido->id_usuario,
                                'id_comic' => $detalle->id_comic,
                            ]);
                            
                            if (!$biblioteca->exists) {
                                $biblioteca->fecha_adquisicion = now();
                                $biblioteca->save();
                            }
                        }
                    }
                }
            }
            
            return response(['success' => true], 200);
        } catch (\Exception $e) {
            Log::error('Error en el webhook de Stripe: ' . $e->getMessage());
            return response(['error' => 'Error interno del servidor'], 500);
        }
    }

    /**
     * Calcular el total del carrito
     *
     * @param array $carrito
     * @return float
     */
    protected function calcularTotal($carrito)
    {
        $total = 0;
        
        foreach ($carrito as $comicId => $item) {
            $comic = Comic::find($comicId);
            if ($comic) {
                $total += $comic->precio * $item['cantidad'];
            }
        }
        
        return $total;
    }
}

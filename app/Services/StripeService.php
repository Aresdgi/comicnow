<?php

namespace App\Services;

use Stripe\StripeClient;
use Stripe\Exception\ApiErrorException;
use App\Models\Pedido;
use App\Models\DetallePedido;
use Illuminate\Support\Facades\Log;

class StripeService
{
    public $stripeClient;
    protected $stripe;    public function __construct()
    {
        $stripeSecret = config('services.stripe.secret');
        
        // Verificar que la clave secreta de Stripe esté configurada
        if (empty($stripeSecret)) {
            Log::error('La clave secreta de Stripe no está configurada. Verifica tu archivo .env');
            throw new \RuntimeException('La configuración de Stripe no está completa. Por favor, verifica tu configuración.');
        }
        
        $this->stripeClient = new StripeClient($stripeSecret);
        $this->stripe = $this->stripeClient; // Por compatibilidad con el código existente
    }    /**
     * Procesar un pago con Cashier
     *
     * @param \App\Models\User $user
     * @param float $amount
     * @param string $paymentMethodId
     * @return \Stripe\PaymentIntent
     * @throws ApiErrorException
     */
    public function processCashierPayment($user, $amount, $paymentMethodId)
    {
        try {
            // Asegurarse de que el usuario tiene un cliente de Stripe
            $user->createOrGetStripeCustomer();
            $user->updateDefaultPaymentMethod($paymentMethodId);
            
            // Realizar el cargo
            $payment = $user->charge(
                $amount * 100, // Stripe trabaja con céntimos
                $paymentMethodId
            );
            
            Log::info('Pago procesado con Cashier', [
                'user_id' => $user->id,
                'amount' => $amount,
                'payment_id' => $payment->id
            ]);
            
            return $payment;
        } catch (\Exception $e) {
            Log::error('Error al procesar el pago con Cashier', [
                'user_id' => $user->id,
                'amount' => $amount,
                'error' => $e->getMessage()
            ]);
            
            throw $e;
        }
    }
            'mode' => 'payment',
            'success_url' => route('checkout.success', ['session_id' => '{CHECKOUT_SESSION_ID}']),
            'cancel_url' => route('checkout.cancel'),
            'client_reference_id' => $pedido->id_pedido,
            'metadata' => [
                'pedido_id' => $pedido->id_pedido,
            ],
            'customer_email' => auth()->user()->email,
        ]);
        
        // Registrar la creación exitosa
        Log::info('Sesión de checkout creada', [
            'session_id' => $session->id,
            'payment_url' => $session->url,
            'pedido_id' => $pedido->id_pedido
        ]);

        return $session->url;
    }

    /**
     * Formatear los items del pedido para Stripe
     *
     * @param Pedido $pedido
     * @return array
     */    protected function getLineItems(Pedido $pedido)
    {
        $lineItems = [];

        foreach ($pedido->detalles as $detalle) {
            $comic = $detalle->comic;
            $lineItems[] = [
                'price_data' => [
                    'currency' => config('services.stripe.currency'),                'product_data' => [
                        'name' => $comic->titulo,
                        'description' => 'Autor: ' . $comic->autor->nombre,
                        'images' => [$comic->portada_url ? url('storage/' . $comic->portada_url) : null],
                    ],
                    'unit_amount' => $this->convertToCents($detalle->precio),
                ],
                'quantity' => $detalle->cantidad,
            ];
        }

        return $lineItems;
    }

    /**
     * Convertir un precio a centavos para Stripe
     *
     * @param float $amount
     * @return int
     */
    protected function convertToCents($amount)
    {
        return (int)($amount * 100);
    }

    /**
     * Verificar el estado de un pago
     *
     * @param string $sessionId
     * @return array
     * @throws ApiErrorException
     */    public function checkSessionStatus($sessionId)
    {
        // Verificar si session_id es el placeholder y no un ID real
        if ($sessionId === '{CHECKOUT_SESSION_ID}') {
            throw new \InvalidArgumentException('ID de sesión inválido: se recibió el placeholder {CHECKOUT_SESSION_ID}');
        }
          // Registrar la verificación en los logs
        Log::info('Verificando estado de sesión de Stripe', ['session_id' => $sessionId]);
        
        try {
            $session = $this->stripe->checkout->sessions->retrieve($sessionId);
            $paymentIntent = $this->stripe->paymentIntents->retrieve($session->payment_intent);
            
            return [
                'status' => $paymentIntent->status,
                'amount' => $paymentIntent->amount / 100,
                'currency' => $paymentIntent->currency,
                'pedido_id' => $session->metadata->pedido_id,
                'payment_intent' => $session->payment_intent
            ];
        } catch (\Stripe\Exception\ApiErrorException $e) {            Log::error('Error de API de Stripe al verificar estado: ' . $e->getMessage(), [
                'session_id' => $sessionId,
                'error_type' => get_class($e),
                'error_code' => $e->getStripeCode(),
                'error_message' => $e->getMessage()
            ]);
            throw $e;
        }
    }

    /**
     * Manejar webhook de Stripe
     *
     * @param string $payload
     * @param string $sigHeader
     * @return array
     */
    public function handleWebhook($payload, $sigHeader)
    {
        $event = null;
        $endpointSecret = config('services.stripe.webhook_secret');

        try {
            $event = \Stripe\Webhook::constructEvent(
                $payload,
                $sigHeader,
                $endpointSecret
            );
        } catch (\UnexpectedValueException $e) {
            // Payload inválido
            return ['error' => 'Invalid payload'];
        } catch (\Stripe\Exception\SignatureVerificationException $e) {
            // Firma inválida
            return ['error' => 'Invalid signature'];
        }

        // Manejar el evento
        if ($event->type === 'checkout.session.completed') {
            $session = $event->data->object;
            $pedidoId = $session->metadata->pedido_id;
            
            $this->actualizarEstadoPedido($pedidoId, 'pagado');
        }

        return ['success' => true];
    }

    /**
     * Actualizar el estado de un pedido
     *
     * @param int $pedidoId
     * @param string $estado
     * @return void
     */    protected function actualizarEstadoPedido($pedidoId, $estado)
    {
        $pedido = Pedido::find($pedidoId);
        if ($pedido) {
            $pedido->estado = $estado;
            $pedido->save();
        }
    }
}

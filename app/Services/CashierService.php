<?php

namespace App\Services;

use Illuminate\Support\Facades\Log;
use App\Models\User;
use App\Models\Pedido;
use App\Models\DetallePedido;
use App\Models\Biblioteca;
use App\Models\Comic;

class CashierService
{
    /**
     * Procesar un pago con Cashier
     *
     * @param User $user
     * @param float $amount
     * @param string $paymentMethodId
     * @return \Stripe\PaymentIntent
     */
    public function procesarPago(User $user, $amount, $paymentMethodId)
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
    
    /**
     * Procesar un pedido completo
     * 
     * @param User $user
     * @param array $carrito
     * @param string $paymentMethodId
     * @return Pedido
     */
    public function procesarPedido(User $user, array $carrito, $paymentMethodId)
    {
        // Calcular el total
        $total = 0;
        foreach ($carrito as $id => $item) {
            $total += $item['precio'] * $item['cantidad'];
        }
        
        // Procesar el pago
        $payment = $this->procesarPago($user, $total, $paymentMethodId);
        
        // Crear el pedido
        $pedido = new Pedido();
        $pedido->id_usuario = $user->id;
        $pedido->fecha = now();
        $pedido->estado = 'pagado';
        $pedido->metodo_pago = 'stripe';
        $pedido->total = $total;
        $pedido->payment_id = $payment->id;
        $pedido->save();
        
        // Crear los detalles del pedido
        foreach ($carrito as $id => $item) {
            $comic = Comic::find($id);
            
            if ($comic) {
                $detalle = new DetallePedido();
                $detalle->id_pedido = $pedido->id_pedido; 
                $detalle->id_comic = $id;
                $detalle->cantidad = $item['cantidad'];
                $detalle->precio_unitario = $comic->precio;
                $detalle->save();
                
                // Añadir a la biblioteca del usuario
                $biblioteca = new Biblioteca();
                $biblioteca->id_usuario = $user->id;
                $biblioteca->id_comic = $id;
                $biblioteca->progreso_lectura = 0;
                $biblioteca->save();
                
                // Actualizar stock del cómic
                $comic->stock -= $item['cantidad'];
                $comic->save();
            }
        }
        
        return $pedido;
    }
}

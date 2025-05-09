<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Pedido;

class PedidoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $pedidos = [
            [
                'id_usuario' => 1, // María García
                'fecha' => '2025-04-15 10:30:00',
                'estado' => 'entregado',
                'metodo_pago' => 'tarjeta',
            ],
            [
                'id_usuario' => 2, // Carlos Rodríguez
                'fecha' => '2025-04-20 15:45:00',
                'estado' => 'procesando',
                'metodo_pago' => 'PayPal',
            ],
            [
                'id_usuario' => 3, // Ana Martínez
                'fecha' => '2025-04-25 09:15:00',
                'estado' => 'enviado',
                'metodo_pago' => 'transferencia',
            ],
            [
                'id_usuario' => 1, // María García
                'fecha' => '2025-05-01 12:00:00',
                'estado' => 'procesando',
                'metodo_pago' => 'tarjeta',
            ],
            [
                'id_usuario' => 4, // Juan López
                'fecha' => '2025-05-05 18:20:00',
                'estado' => 'pendiente',
                'metodo_pago' => 'PayPal',
            ]
        ];

        foreach ($pedidos as $pedido) {
            Pedido::create($pedido);
        }
    }
}

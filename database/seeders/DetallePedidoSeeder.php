<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\DetallePedido;

class DetallePedidoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $detallesPedidos = [
            // Pedido 1 de María García
            [
                'id_pedido' => 1,
                'id_comic' => 1,
                'cantidad' => 1,
                'precio' => 9.99,
            ],
            [
                'id_pedido' => 1,
                'id_comic' => 7,
                'cantidad' => 2,
                'precio' => 11.99,
            ],
            // Pedido 2 de Carlos Rodríguez
            [
                'id_pedido' => 2,
                'id_comic' => 6,
                'cantidad' => 1,
                'precio' => 13.99,
            ],
            [
                'id_pedido' => 2,
                'id_comic' => 8,
                'cantidad' => 1,
                'precio' => 8.99,
            ],
            // Pedido 3 de Ana Martínez
            [
                'id_pedido' => 3,
                'id_comic' => 4,
                'cantidad' => 1,
                'precio' => 15.99,
            ],
            // Pedido 4 de María García
            [
                'id_pedido' => 4,
                'id_comic' => 5,
                'cantidad' => 1,
                'precio' => 18.99,
            ],
            // Pedido 5 de Juan López
            [
                'id_pedido' => 5,
                'id_comic' => 2,
                'cantidad' => 1,
                'precio' => 14.99,
            ],
            [
                'id_pedido' => 5,
                'id_comic' => 3,
                'cantidad' => 1,
                'precio' => 12.99,
            ]
        ];

        foreach ($detallesPedidos as $detalle) {
            DetallePedido::create($detalle);
        }
    }
}

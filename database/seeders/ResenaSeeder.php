<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Resena;

class ResenaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $resenas = [
            [
                'id_usuario' => 1, // María García
                'id_comic' => 1, // The Amazing Spider-Man #1
                'valoracion' => 5,
                'comentario' => 'Una obra maestra del cómic. La historia de Peter Parker sigue siendo tan relevante hoy como cuando se publicó.',
                'fecha' => '2025-04-16 14:30:00'
            ],
            [
                'id_usuario' => 2, // Carlos Rodríguez
                'id_comic' => 2, // Watchmen
                'valoracion' => 5,
                'comentario' => 'Sin duda una de las mejores novelas gráficas de todos los tiempos. Alan Moore es un genio.',
                'fecha' => '2025-04-21 09:45:00'
            ],
            [
                'id_usuario' => 3, // Ana Martínez
                'id_comic' => 4, // The Sandman: Preludes & Nocturnes
                'valoracion' => 4,
                'comentario' => 'Neil Gaiman crea un mundo fascinante de sueños y mitos. Arte visual impresionante.',
                'fecha' => '2025-04-26 16:20:00'
            ],
            [
                'id_usuario' => 4, // Juan López
                'id_comic' => 3, // Sin City: The Hard Goodbye
                'valoracion' => 4,
                'comentario' => 'El estilo noir de Miller es inconfundible. Historia cruda y directa.',
                'fecha' => '2025-05-06 11:10:00'
            ],
            [
                'id_usuario' => 2, // Carlos Rodríguez
                'id_comic' => 6, // Batman: The Dark Knight Returns
                'valoracion' => 5,
                'comentario' => 'La mejor historia de Batman jamás contada. Revolucionó el mundo del cómic.',
                'fecha' => '2025-04-23 18:30:00'
            ],
            [
                'id_usuario' => 1, // María García
                'id_comic' => 7, // V de Vendetta
                'valoracion' => 4,
                'comentario' => 'Una obra política poderosa. El personaje de V es inolvidable.',
                'fecha' => '2025-04-18 20:15:00'
            ],
            [
                'id_usuario' => 3, // Ana Martínez
                'id_comic' => 5, // Mafalda: Tiras Completas
                'valoracion' => 5,
                'comentario' => 'Quino era un genio. Mafalda sigue siendo relevante décadas después.',
                'fecha' => '2025-05-02 13:45:00'
            ],
            [
                'id_usuario' => 4, // Juan López
                'id_comic' => 8, // The Avengers #1
                'valoracion' => 3,
                'comentario' => 'Un clásico que sentó las bases para el universo Marvel, aunque algo simple comparado con los cómics modernos.',
                'fecha' => '2025-05-07 10:30:00'
            ]
        ];

        foreach ($resenas as $resena) {
            Resena::create($resena);
        }
    }
}

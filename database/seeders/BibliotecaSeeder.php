<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Biblioteca;

class BibliotecaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $bibliotecas = [
            [
                'id_usuario' => 1, // María García
                'id_comic' => 1, // The Amazing Spider-Man #1
                'progreso_lectura' => 100.00,
                'ultimo_marcador' => 'Finalizado'
            ],
            [
                'id_usuario' => 1, // María García
                'id_comic' => 7, // V de Vendetta
                'progreso_lectura' => 75.50,
                'ultimo_marcador' => 'Página 156'
            ],
            [
                'id_usuario' => 2, // Carlos Rodríguez
                'id_comic' => 2, // Watchmen
                'progreso_lectura' => 100.00,
                'ultimo_marcador' => 'Finalizado'
            ],
            [
                'id_usuario' => 2, // Carlos Rodríguez
                'id_comic' => 6, // Batman: The Dark Knight Returns
                'progreso_lectura' => 100.00,
                'ultimo_marcador' => 'Finalizado'
            ],
            [
                'id_usuario' => 3, // Ana Martínez
                'id_comic' => 4, // The Sandman: Preludes & Nocturnes
                'progreso_lectura' => 65.30,
                'ultimo_marcador' => 'Capítulo 5, página 23'
            ],
            [
                'id_usuario' => 4, // Juan López
                'id_comic' => 3, // Sin City: The Hard Goodbye
                'progreso_lectura' => 45.80,
                'ultimo_marcador' => 'Página 78'
            ],
            [
                'id_usuario' => 4, // Juan López
                'id_comic' => 8, // The Avengers #1
                'progreso_lectura' => 100.00,
                'ultimo_marcador' => 'Finalizado'
            ],
            [
                'id_usuario' => 3, // Ana Martínez
                'id_comic' => 5, // Mafalda: Tiras Completas
                'progreso_lectura' => 25.00,
                'ultimo_marcador' => 'Página 45'
            ]
        ];

        foreach ($bibliotecas as $biblioteca) {
            Biblioteca::create($biblioteca);
        }
    }
}

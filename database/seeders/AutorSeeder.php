<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Autor;

class AutorSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $autores = [
            [
                'nombre' => 'Stan Lee',
                'biografia' => 'Stanley Martin Lieber, mejor conocido como Stan Lee, fue un escritor y editor de cómics estadounidense, considerado una leyenda en la industria por cocreador de numerosos superhéroes icónicos.',
                'editorial' => 'Marvel Comics',
                'comision' => 15.5
            ],
            [
                'nombre' => 'Alan Moore',
                'biografia' => 'Alan Moore es un escritor británico, conocido principalmente por sus trabajos para cómics como Watchmen, V de Vendetta y From Hell.',
                'editorial' => 'DC Comics',
                'comision' => 18.0
            ],
            [
                'nombre' => 'Frank Miller',
                'biografia' => 'Frank Miller es un dibujante, guionista y director de cine estadounidense, conocido por sus trabajos en cómics como Sin City, 300 y Batman: The Dark Knight Returns.',
                'editorial' => 'DC Comics',
                'comision' => 16.5
            ],
            [
                'nombre' => 'Neil Gaiman',
                'biografia' => 'Neil Richard Gaiman es un autor de fantasía, novelas, cómics, libros para niños y películas de origen británico. Entre sus obras destacan las series de cómics The Sandman.',
                'editorial' => 'Vertigo',
                'comision' => 17.0
            ],
            [
                'nombre' => 'Quino',
                'biografia' => 'Joaquín Salvador Lavado Tejón, conocido como Quino, fue un dibujante argentino creador del personaje Mafalda.',
                'editorial' => 'Editorial Lumen',
                'comision' => 13.5
            ]
        ];

        foreach ($autores as $autor) {
            Autor::create($autor);
        }
    }
}

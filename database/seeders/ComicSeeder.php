<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Comic;

class ComicSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $comics = [
            [
                'titulo' => 'The Amazing Spider-Man #1',
                'descripcion' => 'Primera aparición del Hombre Araña en su propia serie. Peter Parker deberá enfrentarse a su primer gran villano mientras equilibra su vida como estudiante.',
                'portada_url' => 'https://example.com/spiderman1.jpg',
                'archivo_comic' => 'spiderman1.pdf',
                'precio' => 9.99,
                'categoria' => 'superheroes',
                'id_autor' => 1 // Stan Lee
            ],
            [
                'titulo' => 'Watchmen',
                'descripcion' => 'En una historia alternativa donde los superhéroes emergieron en las décadas de 1940 y 1960, el asesinato de un miembro del equipo de vigilantes desencadena una investigación que destapa una terrible conspiración.',
                'portada_url' => 'https://example.com/watchmen.jpg',
                'archivo_comic' => 'watchmen.pdf',
                'precio' => 14.99,
                'categoria' => 'superheroes',
                'id_autor' => 2 // Alan Moore
            ],
            [
                'titulo' => 'Sin City: The Hard Goodbye',
                'descripcion' => 'Marv, un criminal endurecido, busca vengar la muerte de una mujer que fue asesinada mientras dormía a su lado.',
                'portada_url' => 'https://example.com/sincity.jpg',
                'archivo_comic' => 'sincity.pdf',
                'precio' => 12.99,
                'categoria' => 'noir',
                'id_autor' => 3 // Frank Miller
            ],
            [
                'titulo' => 'The Sandman: Preludes & Nocturnes',
                'descripcion' => 'Después de 70 años de prisión, Sueño, uno de los Eternos, debe reclamar los objetos de poder que le fueron robados.',
                'portada_url' => 'https://example.com/sandman.jpg',
                'archivo_comic' => 'sandman.pdf',
                'precio' => 15.99,
                'categoria' => 'fantasia',
                'id_autor' => 4 // Neil Gaiman
            ],
            [
                'titulo' => 'Mafalda: Tiras Completas',
                'descripcion' => 'Colección completa de las tiras cómicas de Mafalda, la niña que se preocupa por la humanidad y la paz mundial mientras cuestiona el mundo de los adultos.',
                'portada_url' => 'https://example.com/mafalda.jpg',
                'archivo_comic' => 'mafalda.pdf',
                'precio' => 18.99,
                'categoria' => 'humor',
                'id_autor' => 5 // Quino
            ],
            [
                'titulo' => 'Batman: The Dark Knight Returns',
                'descripcion' => 'Después de 10 años de ausencia, Bruce Wayne retoma el manto de Batman para combatir el crimen en una Gotham futurista y distópica.',
                'portada_url' => 'https://example.com/darkknight.jpg',
                'archivo_comic' => 'darkknight.pdf',
                'precio' => 13.99,
                'categoria' => 'superheroes',
                'id_autor' => 3 // Frank Miller
            ],
            [
                'titulo' => 'V de Vendetta',
                'descripcion' => 'En un futuro distópico, un misterioso revolucionario conocido como "V" trabaja para destruir a los totalitarios que han sometido a Inglaterra.',
                'portada_url' => 'https://example.com/vendetta.jpg',
                'archivo_comic' => 'vendetta.pdf',
                'precio' => 11.99,
                'categoria' => 'politico',
                'id_autor' => 2 // Alan Moore
            ],
            [
                'titulo' => 'The Avengers #1',
                'descripcion' => 'Los héroes más poderosos de la Tierra se unen para formar los Vengadores, enfrentándose a la amenaza de Loki.',
                'portada_url' => 'https://example.com/avengers.jpg',
                'archivo_comic' => 'avengers.pdf',
                'precio' => 8.99,
                'categoria' => 'superheroes',
                'id_autor' => 1 // Stan Lee
            ]
        ];

        foreach ($comics as $comic) {
            Comic::create($comic);
        }
    }
}

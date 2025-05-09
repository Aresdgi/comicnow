<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Usuario;
use Illuminate\Support\Facades\Hash;

class UsuarioSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $usuarios = [
            [
                'nombre' => 'María García',
                'email' => 'maria@example.com',
                'contraseña' => Hash::make('password123'),
                'direccion' => 'Calle Principal 123, Madrid',
                'preferencias' => 'Marvel, Superhéroes',
                'rol' => 'cliente'
            ],
            [
                'nombre' => 'Carlos Rodríguez',
                'email' => 'carlos@example.com',
                'contraseña' => Hash::make('password123'),
                'direccion' => 'Avenida Central 456, Barcelona',
                'preferencias' => 'DC Comics, Batman',
                'rol' => 'cliente'
            ],
            [
                'nombre' => 'Ana Martínez',
                'email' => 'ana@example.com',
                'contraseña' => Hash::make('password123'),
                'direccion' => 'Plaza Mayor 789, Valencia',
                'preferencias' => 'Manga, Novela Gráfica',
                'rol' => 'cliente'
            ],
            [
                'nombre' => 'Juan López',
                'email' => 'juan@example.com',
                'contraseña' => Hash::make('password123'),
                'direccion' => 'Calle Secundaria 321, Sevilla',
                'preferencias' => 'Europeo, Ciencia Ficción',
                'rol' => 'cliente'
            ],
            [
                'nombre' => 'Admin ComicNow',
                'email' => 'admin@comicnow.com',
                'contraseña' => Hash::make('admin123'),
                'direccion' => 'Oficina Central, Madrid',
                'preferencias' => '',
                'rol' => 'administrador'
            ]
        ];

        foreach ($usuarios as $usuario) {
            Usuario::create($usuario);
        }
    }
}

<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Comentamos estas líneas para evitar duplicados
        // User::factory()->create([
        //     'name' => 'Test User',
        //     'email' => 'test@example.com',
        // ]);

        // Llamar a todos los seeders creados
        $this->call([
            AutorSeeder::class,
            ComicSeeder::class,
            UsuarioSeeder::class,
            PedidoSeeder::class,
            DetallePedidoSeeder::class,
            ResenaSeeder::class,
            BibliotecaSeeder::class,
        ]);
    }
}

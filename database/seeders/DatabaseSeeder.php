<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        DB::table('roles')->insert([
            ['rol' => 'invitado'],
            ['rol' => 'usuario'],
            ['rol' => 'administrador'],
        ]);

        DB::table('users')->insert([
            'name' => 'Ivan',
            'email' => 'joseivan1109@gmail.com',
            'email_verified_at' => null,
            'password' => bcrypt('123456'),
            'rol' => 3, // Asignar el ID del rol correspondiente
            'is_active' => true,
            'created_at' => null,
            'updated_at' => null,
        ]);

        DB::table('users')->insert([
            'name' => 'Jose',
            'email' => 'tolucavolpi666@gmail.com',
            'email_verified_at' => null,
            'password' => bcrypt('123456'),
            'rol' => 3, // Asignar el ID del rol correspondiente
            'is_active' => true,
            'created_at' => null,
            'updated_at' => null,
        ]);

        $juegos = [
            [
            'jugador1' => 1,
            'jugador2' => 2,
            'puntuacion1' => 8,
            'puntuacion2' => 7,
            'estado' => 'finalizado',
            'ganador' => 1
            ],
            [
            'jugador1' => 2,
            'jugador2' => 1,
            'puntuacion1' => 7,
            'puntuacion2' => 8,
            'estado' => 'finalizado',
            'ganador' => 2
            ],
            [
                'jugador1' => 1,
                'jugador2' => 2,
                'puntuacion1' => 7,
                'puntuacion2' => 8,
                'estado' => 'finalizado',
                'ganador' => 2
                ],
        ];

        foreach ($juegos as $juego) {
            DB::table('juegos')->insert($juego);
        }
    }
}

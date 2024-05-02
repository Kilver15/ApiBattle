<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class administradores extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void{

    DB::table('users')->insert([
        [
            'name' => 'administrador2',
            'email' => 'joseivan1109@gmail.com',
            'password' => Hash::make('123456'), // Encriptar la contraseña
            'rol' => 3,
            'is_active' => 1
        ]
    ]);
}

}

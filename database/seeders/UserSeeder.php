<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::create([
            'name' => 'Enrique',
            'ubicacion' => 'Catastro',
            'status' => 'activo',
            'email' => 'correo@correo.com',
            'password' => Hash::make('12345678'),
            'area' => 'Departamento de Operación y Desarrollode Sistemas',
        ])->assignRole('Administrador');

        User::create([
            'name' => 'Jesus Manriquez Vargas',
            'ubicacion' => 'Catastro',
            'status' => 'activo',
            'email' => 'subdirti.irycem@correo.michoacan.gob.mx',
            'password' => Hash::make('12345678'),
            'area' => 'Subdirección de Tecnologías de la Información',
        ])->assignRole('Administrador');

        User::create([
            'name' => 'Omar Alejandro Morales Arellano',
            'ubicacion' => 'Catastro',
            'status' => 'activo',
            'email' => 'alex@hotmail.com',
            'password' => Hash::make('12345678'),
            'area' => 'Subdirección de Tecnologías de la Información',
        ])->assignRole('Director');

        User::create([
            'name' => 'Supervisor Copias',
            'ubicacion' => 'Catastro',
            'status' => 'activo',
            'email' => 'supervisor@hotmail.com',
            'password' => Hash::make('12345678'),
            'area' => 'Subdirección de Tecnologías de la Información',
        ])->assignRole('Supervisor Copias');

        User::create([
            'name' => 'Certificador',
            'ubicacion' => 'Catastro',
            'status' => 'activo',
            'email' => 'certificador@hotmail.com',
            'password' => Hash::make('12345678'),
            'area' => 'Subdirección de Tecnologías de la Información',
        ])->assignRole('Certificador');
    }
}

<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Empresa;
use App\Models\Producto;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        User::firstOrCreate(
            ['email' => 'vendedor@fruna.cl'],
            [
                'name' => 'Benjamín Rivera',
                'password' => Hash::make('contraseña123'),
            ]
        );

    
        Empresa::firstOrCreate(
            ['rut' => '77.777.777-7'],
            ['nombre' => 'Fruna']
        );

        
        $productos = [
            ['nombre' => 'Cocacola', 'precio_unitario' => 800],
            ['nombre' => 'Cereales', 'precio_unitario' => 1200],
            ['nombre' => 'Galletas', 'precio_unitario' => 600],
            ['nombre' => 'Jugos', 'precio_unitario' => 700],
            ['nombre' => 'Chocolates', 'precio_unitario' => 500],
            ['nombre' => 'Papas Fritas', 'precio_unitario' => 900],
            ['nombre' => 'Ramitas', 'precio_unitario' => 650],
            ['nombre' => 'Gomitas', 'precio_unitario' => 550],
        ];

        foreach ($productos as $producto) {
            Producto::firstOrCreate(
                ['nombre' => $producto['nombre']],
                ['precio_unitario' => $producto['precio_unitario']]
            );
        }
    }
}
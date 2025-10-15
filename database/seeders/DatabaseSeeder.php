<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Usuario;
use App\Models\Transportista;
use App\Models\Vehiculo;
use App\Models\Producto;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Crear usuarios de prueba
        Usuario::create([
            'nombre' => 'Administrador',
            'email' => 'admin@example.com',
            'telefono' => '123456789',
            'rol' => 'admin',
            'activo' => true,
        ]);

        Usuario::create([
            'nombre' => 'Cliente Ejemplo',
            'email' => 'cliente@example.com',
            'telefono' => '987654321',
            'rol' => 'cliente',
            'activo' => true,
        ]);

        // Crear transportistas de prueba
        Transportista::create([
            'nombre' => 'Juan Pérez',
            'telefono' => '555123456',
            'licencia' => 'ABC123',
            'empresa' => 'Transportes Pérez',
            'activo' => true,
        ]);

        Transportista::create([
            'nombre' => 'María García',
            'telefono' => '555789012',
            'licencia' => 'XYZ789',
            'empresa' => 'Logística García',
            'activo' => true,
        ]);

        // Crear vehículos de prueba
        Vehiculo::create([
            'transportista_id' => 1,
            'placa' => 'ABC123',
            'tipo' => 'camion',
            'capacidad_kg' => 5000,
            'activo' => true,
        ]);

        Vehiculo::create([
            'transportista_id' => 2,
            'placa' => 'XYZ789',
            'tipo' => 'camioneta',
            'capacidad_kg' => 1000,
            'activo' => true,
        ]);

        // Crear productos de prueba
        Producto::create([
            'nombre' => 'Manzanas',
            'tipo' => 'fruta',
            'unidad' => 'kg',
            'precio_unitario' => 2.50,
            'stock' => 100.5,
            'activo' => true,
        ]);

        Producto::create([
            'nombre' => 'Zanahorias',
            'tipo' => 'verdura',
            'unidad' => 'kg',
            'precio_unitario' => 1.80,
            'stock' => 75.2,
            'activo' => true,
        ]);

        Producto::create([
            'nombre' => 'Plátanos',
            'tipo' => 'fruta',
            'unidad' => 'kg',
            'precio_unitario' => 1.20,
            'stock' => 200.0,
            'activo' => true,
        ]);
    }
}

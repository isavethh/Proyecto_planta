<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Schema;
use PDO;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Forzar SQLite si el driver MySQL/pgsql no está disponible
        try {
            $drivers = PDO::getAvailableDrivers();
            if (!in_array('mysql', $drivers) && !in_array('pgsql', $drivers)) {
                Config::set('database.default', 'sqlite');
            }
        } catch (\Throwable $e) {
            Config::set('database.default', 'sqlite');
        }

        // Asegurar que exista el archivo sqlite
        if (config('database.default') === 'sqlite') {
            $dbPath = config('database.connections.sqlite.database');
            if ($dbPath && !file_exists($dbPath)) {
                @touch($dbPath);
            }

            // Crear tabla 'envios' si no existe (instalación cero)
            if (!Schema::hasTable('envios')) {
                Schema::create('envios', function ($table) {
                    $table->id();
                    $table->string('direccion_desde_planta');
                    $table->string('categoria_producto');
                    $table->string('producto');
                    $table->decimal('peso_producto_unidad', 10, 2);
                    $table->integer('unidades_totales');
                    $table->string('transporte_sugerido');
                    $table->string('transporte_seleccionado')->nullable();
                    $table->string('estado')->default('pendiente');
                    $table->decimal('precio_producto', 10, 2);
                    $table->timestamp('fecha_creacion')->useCurrent();
                    $table->timestamp('fecha_confirmacion')->nullable();
                    $table->unsignedBigInteger('transportista_id')->nullable();
                    $table->unsignedBigInteger('vehiculo_id')->nullable();
                    $table->string('cliente_id');
                    $table->index(['estado']);
                    $table->index(['cliente_id']);
                    $table->index(['fecha_creacion']);
                });
            }
        }
    }
}

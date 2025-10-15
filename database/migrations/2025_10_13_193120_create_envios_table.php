<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('envios', function (Blueprint $table) {
            $table->id();
            $table->string('direccion_desde_planta');
            $table->enum('categoria_producto', ['electronica', 'ropa', 'alimentos', 'medicinas', 'libros', 'otros']);
            $table->string('producto');
            $table->decimal('peso_producto_unidad', 10, 2);
            $table->integer('unidades_totales');
            $table->enum('transporte_sugerido', array_keys(\App\Models\Envio::TRANSPORTES_DISPONIBLES));
            $table->enum('transporte_seleccionado', array_keys(\App\Models\Envio::TRANSPORTES_DISPONIBLES))->nullable();
            $table->enum('estado', ['pendiente', 'confirmado', 'recibido'])->default('pendiente');
            $table->decimal('precio_producto', 10, 2);
            $table->timestamp('fecha_creacion')->useCurrent();
            $table->timestamp('fecha_confirmacion')->nullable();
            $table->unsignedBigInteger('transportista_id')->nullable();
            $table->unsignedBigInteger('vehiculo_id')->nullable();
            $table->string('cliente_id'); // ID del usuario que creó el envío

            // Índices y claves foráneas
            $table->index(['estado']);
            $table->index(['cliente_id']);
            $table->index(['fecha_creacion']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('envios');
    }
};

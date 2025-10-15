<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('vehiculos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('transportista_id')
                  ->constrained('transportistas')
                  ->cascadeOnUpdate()
                  ->restrictOnDelete();
            $table->string('placa', 20)->unique();
            $table->enum('tipo', ['camion','camioneta','furgon','moto'])->default('camioneta');
            $table->integer('capacidad_kg')->nullable();
            $table->boolean('activo')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('vehiculos');
    }
};

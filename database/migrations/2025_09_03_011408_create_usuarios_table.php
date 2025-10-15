<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('usuarios', function (Blueprint $table) {
            $table->id();
            $table->string('nombre', 120);
            $table->string('email', 150)->unique()->nullable(); // si no usarás login, puede ser NULL
            $table->string('telefono', 30)->nullable();
            $table->enum('rol', ['admin','cliente','transportista'])->default('cliente'); // ajusta los roles si quieres
            $table->boolean('activo')->default(true);
            // $table->string('password')->nullable(); 
            $table->timestamps(); 
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('usuarios');
    }
};

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('transportistas', function (Blueprint $table) {
            $table->id();
            $table->string('nombre', 120);
            $table->string('telefono', 30)->nullable();
            $table->string('licencia', 50)->nullable();
            $table->string('empresa', 120)->nullable();
            $table->boolean('activo')->default(true);
            $table->timestamps(); // quítalo si no quieres timestamps
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('transportistas');
    }
};
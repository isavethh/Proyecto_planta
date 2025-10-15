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
        Schema::table('envios', function (Blueprint $table) {
            if (!Schema::hasColumn('envios', 'destino_direccion')) {
                $table->string('destino_direccion')->nullable()->after('direccion_desde_planta');
            }
            if (!Schema::hasColumn('envios', 'destino_lat')) {
                $table->decimal('destino_lat', 10, 7)->nullable()->after('destino_direccion');
            }
            if (!Schema::hasColumn('envios', 'destino_lng')) {
                $table->decimal('destino_lng', 10, 7)->nullable()->after('destino_lat');
            }
            if (!Schema::hasColumn('envios', 'distancia_km')) {
                $table->decimal('distancia_km', 10, 2)->nullable()->after('destino_lng');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('envios', function (Blueprint $table) {
            if (Schema::hasColumn('envios', 'distancia_km')) {
                $table->dropColumn('distancia_km');
            }
            if (Schema::hasColumn('envios', 'destino_lng')) {
                $table->dropColumn('destino_lng');
            }
            if (Schema::hasColumn('envios', 'destino_lat')) {
                $table->dropColumn('destino_lat');
            }
            if (Schema::hasColumn('envios', 'destino_direccion')) {
                $table->dropColumn('destino_direccion');
            }
        });
    }
};




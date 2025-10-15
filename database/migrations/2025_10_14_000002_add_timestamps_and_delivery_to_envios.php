<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('envios', function (Blueprint $table) {
            if (!Schema::hasColumn('envios', 'created_at')) {
                $table->timestamp('created_at')->nullable();
            }
            if (!Schema::hasColumn('envios', 'updated_at')) {
                $table->timestamp('updated_at')->nullable();
            }
            if (!Schema::hasColumn('envios', 'fecha_entrega_deseada')) {
                $table->timestamp('fecha_entrega_deseada')->nullable()->after('fecha_confirmacion');
            }
        });
    }

    public function down(): void
    {
        Schema::table('envios', function (Blueprint $table) {
            if (Schema::hasColumn('envios', 'fecha_entrega_deseada')) {
                $table->dropColumn('fecha_entrega_deseada');
            }
            if (Schema::hasColumn('envios', 'updated_at')) {
                $table->dropColumn('updated_at');
            }
            if (Schema::hasColumn('envios', 'created_at')) {
                $table->dropColumn('created_at');
            }
        });
    }
};




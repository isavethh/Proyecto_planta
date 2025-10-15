<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('envios', function (Blueprint $table) {
            if (!Schema::hasColumn('envios', 'items')) {
                $table->json('items')->nullable()->after('precio_producto');
            }
        });
    }

    public function down(): void
    {
        Schema::table('envios', function (Blueprint $table) {
            if (Schema::hasColumn('envios', 'items')) {
                $table->dropColumn('items');
            }
        });
    }
};



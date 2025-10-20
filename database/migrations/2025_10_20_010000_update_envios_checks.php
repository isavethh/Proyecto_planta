<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // Ajustar CHECK constraints para Postgres: permitir nuevos y antiguos valores
        DB::statement('ALTER TABLE "envios" DROP CONSTRAINT IF EXISTS "envios_categoria_producto_check"');
        DB::statement('ALTER TABLE "envios" DROP CONSTRAINT IF EXISTS "envios_transporte_sugerido_check"');
        DB::statement('ALTER TABLE "envios" DROP CONSTRAINT IF EXISTS "envios_transporte_seleccionado_check"');
        DB::statement('ALTER TABLE "envios" DROP CONSTRAINT IF EXISTS "envios_estado_check"');

        // Categorías: nuevas + antiguas para compatibilidad
        DB::statement("ALTER TABLE \"envios\" ADD CONSTRAINT \"envios_categoria_producto_check\" CHECK (categoria_producto IN ('frutas','verduras','granos','lacteos','medicamentos','electronica','ropa','alimentos','medicinas','libros','otros'))");

        // Transportes: nuevos (aislado, ventilado, refrigerado) + antiguos (camion_*, avion, barco)
        DB::statement("ALTER TABLE \"envios\" ADD CONSTRAINT \"envios_transporte_sugerido_check\" CHECK (transporte_sugerido IN ('aislado','ventilado','refrigerado','camion_pequeno','camion_mediano','camion_grande','camion_refrigerado','avion_carga','barco'))");
        DB::statement("ALTER TABLE \"envios\" ADD CONSTRAINT \"envios_transporte_seleccionado_check\" CHECK (transporte_seleccionado IN ('aislado','ventilado','refrigerado','camion_pequeno','camion_mediano','camion_grande','camion_refrigerado','avion_carga','barco'))");

        // Estados: agregar 'en_proceso'
        DB::statement("ALTER TABLE \"envios\" ADD CONSTRAINT \"envios_estado_check\" CHECK (estado IN ('pendiente','confirmado','en_proceso','recibido'))");
    }

    public function down(): void
    {
        // Revertir a los checks originales (según migración inicial)
        DB::statement('ALTER TABLE "envios" DROP CONSTRAINT IF EXISTS "envios_categoria_producto_check"');
        DB::statement('ALTER TABLE "envios" DROP CONSTRAINT IF EXISTS "envios_transporte_sugerido_check"');
        DB::statement('ALTER TABLE "envios" DROP CONSTRAINT IF EXISTS "envios_transporte_seleccionado_check"');
        DB::statement('ALTER TABLE "envios" DROP CONSTRAINT IF EXISTS "envios_estado_check"');

        DB::statement("ALTER TABLE \"envios\" ADD CONSTRAINT \"envios_categoria_producto_check\" CHECK (categoria_producto IN ('electronica','ropa','alimentos','medicinas','libros','otros'))");
        DB::statement("ALTER TABLE \"envios\" ADD CONSTRAINT \"envios_transporte_sugerido_check\" CHECK (transporte_sugerido IN ('camion_pequeno','camion_mediano','camion_grande','camion_refrigerado','avion_carga','barco'))");
        DB::statement("ALTER TABLE \"envios\" ADD CONSTRAINT \"envios_transporte_seleccionado_check\" CHECK (transporte_seleccionado IN ('camion_pequeno','camion_mediano','camion_grande','camion_refrigerado','avion_carga','barco'))");
        DB::statement("ALTER TABLE \"envios\" ADD CONSTRAINT \"envios_estado_check\" CHECK (estado IN ('pendiente','confirmado','recibido'))");
    }
};





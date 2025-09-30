<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        // Solo ejecutar si es PostgreSQL
        if (config('database.default') !== 'pgsql') {
            return;
        }

        // Modificar tabla proyectos para PostgreSQL
        Schema::table('proyectos', function (Blueprint $table) {
            // PostgreSQL maneja JSON nativamente
            DB::statement('ALTER TABLE proyectos ALTER COLUMN cargar_evidencias TYPE JSON USING cargar_evidencias::JSON');
        });

        // Agregar constraints para campos ENUM
        DB::statement("ALTER TABLE proyectos ADD CONSTRAINT check_estado \
                      CHECK (estado IN ('activo', 'inactivo', 'cerrado'))");

        DB::statement("ALTER TABLE audit_log ADD CONSTRAINT check_operation \
                      CHECK (operation IN ('INSERT', 'UPDATE', 'DELETE'))");

        DB::statement("ALTER TABLE access_requests ADD CONSTRAINT check_status \
                      CHECK (status IN ('pending', 'approved', 'rejected'))");
    }

    public function down()
    {
        if (config('database.default') !== 'pgsql') {
            return;
        }

        DB::statement("ALTER TABLE proyectos DROP CONSTRAINT IF EXISTS check_estado");
        DB::statement("ALTER TABLE audit_log DROP CONSTRAINT IF EXISTS check_operation");
        DB::statement("ALTER TABLE access_requests DROP CONSTRAINT IF EXISTS check_status");
    }
    {
        //
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('prorrogas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('proyecto_id')->constrained('proyectos')->cascadeOnDelete();
            $table->foreignId('solicitado_por')->constrained('users')->cascadeOnDelete();

            // Clasificación legal colombiana (Ley 80/1993)
            $table->string('tipo_solicitud', 15)->default('prorroga'); // prorroga | suspension
            $table->string('causa_tipo', 25);       // fuerza_mayor | caso_fortuito | necesidad_servicio | mutuo_acuerdo
            $table->string('causa_subtipo', 30)->nullable(); // climatica, sismica, orden_publico, etc.

            // Detalles de extensión
            $table->integer('dias_solicitados');
            $table->date('fecha_fin_original');
            $table->date('fecha_fin_propuesta');
            $table->text('justificacion');
            $table->text('impacto_descripcion')->nullable();

            // Compliance colombiano (condicional: fuerza mayor)
            $table->string('departamento_afectado', 50)->nullable();
            $table->string('referencia_ideam', 100)->nullable();
            $table->string('referencia_declaratoria', 100)->nullable();

            // Evidencia (un solo archivo consolidado)
            $table->string('evidencia_path')->nullable();
            $table->string('evidencia_nombre_original')->nullable();

            // Flujo de aprobación
            $table->string('estado', 15)->default('pendiente'); // pendiente | aprobada | rechazada
            $table->foreignId('aprobado_por')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamp('aprobado_en')->nullable();
            $table->foreignId('rechazado_por')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamp('rechazado_en')->nullable();
            $table->text('decision_comentario')->nullable();

            $table->timestamps();

            // Índices
            $table->index(['proyecto_id', 'estado']);
            $table->index('estado');
            $table->index('solicitado_por');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('prorrogas');
    }
};

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('resource_access_requests', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('permission_id')->constrained()->cascadeOnDelete();
            $table->foreignId('proyecto_id')->nullable()->constrained('proyectos')->nullOnDelete();

            $table->string('requested_access_level', 20)->default('lectura');
            $table->text('justification');
            $table->string('duration_type', 15)->default('temporal');
            $table->timestamp('starts_at')->nullable();
            $table->timestamp('expires_at')->nullable();

            // Risk engine
            $table->smallInteger('risk_score')->nullable();
            $table->string('risk_level', 10)->nullable();
            $table->json('risk_factors')->nullable();

            // Status & approval flow
            $table->string('status', 15)->default('pendiente');
            $table->boolean('requires_double_approval')->default(false);

            $table->foreignId('approved_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamp('approved_at')->nullable();
            $table->foreignId('second_approved_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamp('second_approved_at')->nullable();

            $table->foreignId('rejected_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamp('rejected_at')->nullable();

            $table->text('decision_rationale')->nullable();

            $table->foreignId('revoked_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamp('revoked_at')->nullable();
            $table->text('revocation_reason')->nullable();

            $table->timestamps();

            $table->index(['status', 'risk_level']);
            $table->index('user_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('resource_access_requests');
    }
};

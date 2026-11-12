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
        Schema::create('solicitud_permisos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('profesor_id')->constrained('profesores')->cascadeOnDelete();
            $table->date('fecha_inicio');
            $table->date('fecha_fin');
            $table->unsignedInteger('dias_solicitados');
            $table->text('motivo');
            $table->string('estado')->default('pendiente');
            $table->text('respuesta')->nullable();
            $table->foreignId('respondido_por_id')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamp('respondido_en')->nullable();
            $table->timestamps();

            $table->index(['profesor_id', 'estado']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('solicitud_permisos');
    }
};

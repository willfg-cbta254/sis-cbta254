<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('calificaciones', function (Blueprint $table) {
            $table->id();
            $table->foreignId('alumno_id')->constrained()->cascadeOnDelete();
            $table->foreignId('materia_id')->constrained()->cascadeOnDelete();
            $table->foreignId('profesor_id')->constrained('profesores')->cascadeOnDelete();
            $table->foreignId('periodo_escolar_id')->constrained('periodos_escolares')->cascadeOnDelete();
            $table->string('tipo', 20);
            $table->decimal('valor', 5, 2);
            $table->decimal('porcentaje', 5, 2);
            $table->string('descripcion', 255)->nullable();
            $table->date('fecha_evaluacion');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('calificaciones');
    }
};

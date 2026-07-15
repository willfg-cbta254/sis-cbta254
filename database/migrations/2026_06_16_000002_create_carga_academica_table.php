<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('carga_academica', function (Blueprint $table) {
            $table->id();
            $table->foreignId('profesor_id')->constrained('profesores')->cascadeOnDelete();
            $table->foreignId('materia_id')->constrained('materias')->cascadeOnDelete();
            $table->foreignId('grupo_id')->constrained('grupos')->cascadeOnDelete();
            $table->foreignId('periodo_escolar_id')->constrained('periodos_escolares')->cascadeOnDelete();
            $table->timestamps();

            $table->unique(['profesor_id', 'materia_id', 'grupo_id', 'periodo_escolar_id'], 'carga_academica_unique');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('carga_academica');
    }
};

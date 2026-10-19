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
        Schema::create('tutoria_incidencia_alumnos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tutoria_incidencia_id')->constrained('tutoria_incidencias')->cascadeOnDelete();
            $table->foreignId('alumno_id')->constrained('alumnos')->cascadeOnDelete();
            $table->timestamps();
            $table->unique(['tutoria_incidencia_id', 'alumno_id'], 'tia_incidencia_alumno_unique');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tutoria_incidencia_alumnos');
    }
};

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('inscripciones', function (Blueprint $table) {
            $table->id();
            $table->foreignId('alumno_id')->constrained()->cascadeOnDelete();
            $table->foreignId('grupo_id')->constrained()->cascadeOnDelete();
            $table->foreignId('periodo_escolar_id')->constrained('periodos_escolares')->cascadeOnDelete();
            $table->date('fecha_inscripcion')->default(now());
            $table->string('status', 15)->default('Inscrito');
            $table->timestamps();

            $table->unique(['alumno_id', 'periodo_escolar_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('inscripciones');
    }
};

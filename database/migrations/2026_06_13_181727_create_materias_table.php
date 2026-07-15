<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('materias', function (Blueprint $table) {
            $table->id();
            $table->string('nombre', 120);
            $table->string('clave', 30)->unique();
            $table->foreignId('semestre_id')->constrained()->cascadeOnDelete();
            $table->foreignId('carrera_id')->nullable()->constrained()->cascadeOnDelete();
            $table->tinyInteger('horas_semana')->default(3);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('materias');
    }
};

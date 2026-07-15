<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('horarios', function (Blueprint $table) {
            $table->id();
            $table->foreignId('carga_academica_id')->constrained('carga_academica')->cascadeOnDelete();
            $table->string('dia', 10);
            $table->time('hora_inicio');
            $table->time('hora_fin');
            $table->string('aula', 50)->nullable();
            $table->timestamps();

            $table->unique(['carga_academica_id', 'dia', 'hora_inicio'], 'horarios_unique');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('horarios');
    }
};

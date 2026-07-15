<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('grupos', function (Blueprint $table) {
            $table->id();
            $table->string('nombre', 10);
            $table->foreignId('semestre_id')->constrained();
            $table->foreignId('carrera_id')->constrained();
            $table->foreignId('generacion_id')->constrained('generaciones');
            $table->string('turno', 15);
            $table->tinyInteger('capacidad')->default(40);
            $table->boolean('activo')->default(true);
            $table->timestamps();

            $table->unique(['nombre', 'semestre_id', 'carrera_id', 'generacion_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('grupos');
    }
};

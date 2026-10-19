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
        Schema::create('tutoria_incidencias', function (Blueprint $table) {
            $table->id();
            $table->foreignId('grupo_id')->constrained('grupos')->cascadeOnDelete();
            $table->foreignId('tutor_id')->constrained('profesores')->cascadeOnDelete();
            $table->enum('tipo', ['grupal', 'individual']);
            $table->text('descripcion');
            $table->foreignId('periodo_escolar_id')->constrained('periodos_escolares')->cascadeOnDelete();
            $table->foreignId('registrado_por_id')->nullable()->constrained('profesores')->nullOnDelete();
            $table->json('alumnos_ids')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tutoria_incidencias');
    }
};

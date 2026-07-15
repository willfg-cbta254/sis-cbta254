<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('horas_labor_social', function (Blueprint $table) {
            $table->id();
            $table->foreignId('alumno_id')->constrained()->onDelete('cascade');
            $table->foreignId('falta_reglamentaria_id')->constrained('faltas_reglamentarias')->onDelete('cascade');
            $table->decimal('horas_asignadas', 5, 1);
            $table->decimal('horas_cumplidas', 5, 1)->default(0);
            $table->date('fecha_asignacion');
            $table->string('estado')->default('pendiente');
            $table->text('observaciones')->nullable();
            $table->foreignId('registrado_por')->nullable()->constrained('users')->onDelete('set null');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('horas_labor_social');
    }
};

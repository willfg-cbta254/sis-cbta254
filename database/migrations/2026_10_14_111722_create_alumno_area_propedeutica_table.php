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
        Schema::create('alumno_area_propedeutica', function (Blueprint $table) {
            $table->id();
            $table->foreignId('alumno_id')->constrained('alumnos')->cascadeOnDelete();
            $table->foreignId('area_propedeutica_id')->constrained('areas_propedeuticas')->cascadeOnDelete();
            $table->foreignId('periodo_escolar_id')->nullable()->constrained('periodos_escolares')->nullOnDelete();
            $table->timestamps();

            $table->unique('alumno_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('alumno_area_propedeutica');
    }
};

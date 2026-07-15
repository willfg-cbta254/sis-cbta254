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
        Schema::table('calificaciones', function (Blueprint $table) {
            $table->dropColumn(['tipo', 'valor', 'porcentaje', 'descripcion', 'fecha_evaluacion']);

            $table->decimal('parcial1', 5, 2)->nullable()->after('grupo_id');
            $table->decimal('parcial2', 5, 2)->nullable()->after('parcial1');
            $table->decimal('parcial3', 5, 2)->nullable()->after('parcial2');
            $table->decimal('promedio', 5, 2)->nullable()->after('parcial3');
            $table->decimal('extraordinario', 5, 2)->nullable()->after('promedio');
            $table->decimal('curso_intersemestral', 5, 2)->nullable()->after('extraordinario');
            $table->boolean('recursamiento')->default(false)->after('curso_intersemestral');
        });
    }

    public function down(): void
    {
        Schema::table('calificaciones', function (Blueprint $table) {
            $table->dropColumn(['parcial1', 'parcial2', 'parcial3', 'promedio', 'extraordinario', 'curso_intersemestral', 'recursamiento']);

            $table->string('tipo', 20)->after('periodo_escolar_id');
            $table->decimal('valor', 5, 2)->after('tipo');
            $table->decimal('porcentaje', 5, 2)->after('valor');
            $table->string('descripcion', 255)->nullable()->after('porcentaje');
            $table->date('fecha_evaluacion')->after('descripcion');
        });
    }
};

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('carga_academica', function (Blueprint $table) {
            $table->dropForeign(['profesor_id']);
            $table->dropUnique('carga_academica_unique');
        });

        Schema::table('carga_academica', function (Blueprint $table) {
            $table->unique(['materia_id', 'grupo_id', 'periodo_escolar_id'], 'carga_academica_unique');
            $table->foreign('profesor_id')->references('id')->on('profesores')->cascadeOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('carga_academica', function (Blueprint $table) {
            $table->dropForeign(['profesor_id']);
            $table->dropUnique('carga_academica_unique');
        });

        Schema::table('carga_academica', function (Blueprint $table) {
            $table->unique(['profesor_id', 'materia_id', 'grupo_id', 'periodo_escolar_id'], 'carga_academica_unique');
            $table->foreign('profesor_id')->references('id')->on('profesores')->cascadeOnDelete();
        });
    }
};

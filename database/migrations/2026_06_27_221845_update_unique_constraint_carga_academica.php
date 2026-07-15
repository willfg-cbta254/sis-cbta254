<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('carga_academica', function (Blueprint $table) {
            $table->dropUnique('carga_academica_unique');
        });

        Schema::table('carga_academica', function (Blueprint $table) {
            $table->unique(['materia_id', 'grupo_id', 'periodo_escolar_id'], 'carga_academica_unique');
        });
    }

    public function down(): void
    {
        Schema::table('carga_academica', function (Blueprint $table) {
            $table->dropUnique('carga_academica_unique');
        });

        Schema::table('carga_academica', function (Blueprint $table) {
            $table->unique(['profesor_id', 'materia_id', 'grupo_id', 'periodo_escolar_id'], 'carga_academica_unique');
        });
    }
};

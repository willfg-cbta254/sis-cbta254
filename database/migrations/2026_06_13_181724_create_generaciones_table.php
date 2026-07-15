<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('generaciones', function (Blueprint $table) {
            $table->id();
            $table->string('clave', 9)->unique();
            $table->year('anio_inicio');
            $table->year('anio_fin');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('generaciones');
    }
};

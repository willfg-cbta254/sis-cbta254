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
        Schema::create('areas_propedeuticas', function (Blueprint $table) {
            $table->id();
            $table->string('clave', 30)->unique();
            $table->string('nombre', 120);
            $table->text('descripcion')->nullable();
            $table->unsignedSmallInteger('capacidad')->default(0);
            $table->boolean('activo')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('areas_propedeuticas');
    }
};

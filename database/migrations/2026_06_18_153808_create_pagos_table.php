<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pagos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('alumno_id')->constrained()->cascadeOnDelete();
            $table->foreignId('concepto_pago_id')->constrained('conceptos_pagos')->cascadeOnDelete();
            $table->decimal('monto', 10, 2);
            $table->string('referencia', 100)->nullable();
            $table->date('fecha_pago');
            $table->string('metodo_pago', 30);
            $table->text('observaciones')->nullable();
            $table->string('estatus', 20)->default('pagado');
            $table->foreignId('usuario_id')->constrained('users')->cascadeOnDelete();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pagos');
    }
};

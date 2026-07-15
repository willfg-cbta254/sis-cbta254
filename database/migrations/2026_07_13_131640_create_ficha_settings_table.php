<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('ficha_settings', function (Blueprint $table) {
            $table->id();
            $table->boolean('is_open')->default(false);
            $table->string('ciclo_escolar', 20);
            $table->integer('current_folio')->default(100);
            $table->timestamps();
        });

        DB::table('ficha_settings')->insert([
            'is_open' => false,
            'ciclo_escolar' => date('Y').'-'.(date('Y') + 1),
            'current_folio' => 100,
        ]);
    }

    public function down(): void
    {
        Schema::dropIfExists('ficha_settings');
    }
};

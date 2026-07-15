<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('fichas', function (Blueprint $table) {
            $table->id();
            $table->string('folio', 50)->unique();
            $table->integer('folio_num');
            $table->string('ciclo_escolar', 20);

            $table->string('nombre_completo');
            $table->date('fecha_nacimiento');
            $table->integer('edad');
            $table->enum('genero', ['femenino', 'masculino']);
            $table->text('direccion');
            $table->string('num_celular', 20);
            $table->string('correo_electronico');
            $table->string('localidad');
            $table->string('municipio');
            $table->string('secundaria_procedencia');
            $table->enum('tipo_secundaria', ['tecnica', 'telesecundaria']);
            $table->string('localidad_secundaria');
            $table->enum('turno', ['matutino', 'vespertino']);
            $table->decimal('promedio', 4, 2);

            $table->string('tutor_nombre_completo');
            $table->string('tutor_num_celular', 20);
            $table->string('tutor_correo_electronico');

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('fichas');
    }
};

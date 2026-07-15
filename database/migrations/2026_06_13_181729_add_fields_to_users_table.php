<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('curp', 18)->nullable()->unique()->after('email');
            $table->string('telefono', 15)->nullable()->after('curp');
            $table->date('fecha_nacimiento')->nullable()->after('telefono');
            $table->string('direccion', 255)->nullable()->after('fecha_nacimiento');
            $table->boolean('activo')->default(true)->after('direccion');
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['curp', 'telefono', 'fecha_nacimiento', 'direccion', 'activo']);
        });
    }
};

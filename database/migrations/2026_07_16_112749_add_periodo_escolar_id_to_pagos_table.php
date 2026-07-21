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
        Schema::table('pagos', function (Blueprint $table) {
            $table->foreignId('periodo_escolar_id')
                ->nullable()
                ->constrained('periodos_escolares')
                ->nullOnDelete()
                ->after('concepto_pago_id');
        });
    }

    public function down(): void
    {
        Schema::table('pagos', function (Blueprint $table) {
            $table->dropForeign(['periodo_escolar_id']);
            $table->dropColumn('periodo_escolar_id');
        });
    }
};

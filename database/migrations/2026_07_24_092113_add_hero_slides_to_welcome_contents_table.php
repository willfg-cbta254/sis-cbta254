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
        Schema::table('welcome_contents', function (Blueprint $table) {
            $table->json('hero_slides')->nullable()->after('hero_btn_secondary_text');
        });
    }

    public function down(): void
    {
        Schema::table('welcome_contents', function (Blueprint $table) {
            $table->dropColumn('hero_slides');
        });
    }
};

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('welcome_contents', function (Blueprint $table) {
            $table->id();

            // Hero
            $table->string('hero_badge')->nullable();
            $table->string('hero_title')->nullable();
            $table->text('hero_description')->nullable();
            $table->string('hero_image')->nullable();
            $table->string('hero_btn_text')->nullable();
            $table->string('hero_btn_secondary_text')->nullable();

            // Meta
            $table->string('meta_title')->nullable();
            $table->text('meta_description')->nullable();

            // Careers (JSON)
            $table->json('careers')->nullable();

            // Gallery (JSON)
            $table->json('gallery')->nullable();

            // Announcements (JSON)
            $table->json('announcements')->nullable();

            // Contact
            $table->string('contact_address')->nullable();
            $table->string('contact_email')->nullable();
            $table->string('contact_phone')->nullable();

            // Footer
            $table->text('footer_description')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('welcome_contents');
    }
};

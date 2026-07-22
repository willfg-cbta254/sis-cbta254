<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('galleries', function (Blueprint $table) {
            $table->foreignId('periodo_escolar_id')
                ->nullable()
                ->constrained('periodos_escolares')
                ->nullOnDelete()
                ->after('id');

            $table->timestamp('published_at')->nullable()->after('updated_at');
        });

        $activePeriodo = DB::table('periodos_escolares')->where('activo', true)->first();

        if ($activePeriodo) {
            DB::table('galleries')->update(['periodo_escolar_id' => $activePeriodo->id]);
        }

        $welcomeContents = DB::table('welcome_contents')->get();

        foreach ($welcomeContents as $wc) {
            $gallery = json_decode($wc->gallery ?? '[]', true);

            foreach ($gallery as $item) {
                if (! empty($item['image'])) {
                    DB::table('galleries')->insert([
                        'periodo_escolar_id' => $activePeriodo?->id,
                        'image' => $item['image'],
                        'title' => $item['label'] ?? '',
                        'is_active' => true,
                        'created_at' => $wc->created_at,
                        'updated_at' => $wc->updated_at,
                    ]);
                }
            }
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('galleries', function (Blueprint $table) {
            $table->dropForeign(['periodo_escolar_id']);
            $table->dropColumn('periodo_escolar_id');
            $table->dropColumn('published_at');
        });
    }
};

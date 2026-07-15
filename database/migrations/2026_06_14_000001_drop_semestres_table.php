<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        $driver = DB::getDriverName();

        if ($driver === 'mysql') {
            $dbName = DB::getDatabaseName();

            $existingFks = DB::select("
                SELECT CONSTRAINT_NAME, TABLE_NAME
                FROM information_schema.TABLE_CONSTRAINTS
                WHERE TABLE_SCHEMA = ? AND CONSTRAINT_TYPE = 'FOREIGN KEY'
                  AND CONSTRAINT_NAME IN ('alumnos_semestre_actual_id_foreign', 'materias_semestre_id_foreign', 'grupos_semestre_id_foreign')
            ", [$dbName]);

            foreach ($existingFks as $fk) {
                DB::statement("ALTER TABLE `{$fk->TABLE_NAME}` DROP FOREIGN KEY `{$fk->CONSTRAINT_NAME}`");
            }

            $existingUnique = DB::select("
                SELECT INDEX_NAME, TABLE_NAME
                FROM information_schema.STATISTICS
                WHERE TABLE_SCHEMA = ? AND INDEX_NAME = 'grupos_nombre_semestre_id_carrera_id_generacion_id_unique'
                GROUP BY INDEX_NAME, TABLE_NAME
            ", [$dbName]);

            if (! empty($existingUnique)) {
                Schema::table('grupos', function (Blueprint $table) {
                    $table->dropUnique('grupos_nombre_semestre_id_carrera_id_generacion_id_unique');
                });
            }
        } else {
            Schema::table('grupos', function (Blueprint $table) {
                $table->dropUnique('grupos_nombre_semestre_id_carrera_id_generacion_id_unique');
            });
        }

        Schema::table('alumnos', function (Blueprint $table) {
            $table->string('semestre_actual_id', 20)->change();
        });

        Schema::table('materias', function (Blueprint $table) {
            $table->string('semestre_id', 20)->change();
        });

        Schema::table('grupos', function (Blueprint $table) {
            $table->string('semestre_id', 20)->change();
        });

        DB::statement("UPDATE alumnos SET semestre_actual_id = CASE semestre_actual_id
            WHEN '1' THEN 'primero' WHEN '2' THEN 'segundo'
            WHEN '3' THEN 'tercero' WHEN '4' THEN 'cuarto'
            WHEN '5' THEN 'quinto' WHEN '6' THEN 'sexto'
        END");

        DB::statement("UPDATE materias SET semestre_id = CASE semestre_id
            WHEN '1' THEN 'primero' WHEN '2' THEN 'segundo'
            WHEN '3' THEN 'tercero' WHEN '4' THEN 'cuarto'
            WHEN '5' THEN 'quinto' WHEN '6' THEN 'sexto'
        END");

        DB::statement("UPDATE grupos SET semestre_id = CASE semestre_id
            WHEN '1' THEN 'primero' WHEN '2' THEN 'segundo'
            WHEN '3' THEN 'tercero' WHEN '4' THEN 'cuarto'
            WHEN '5' THEN 'quinto' WHEN '6' THEN 'sexto'
        END");

        Schema::table('grupos', function (Blueprint $table) {
            $table->unique(['nombre', 'semestre_id', 'carrera_id', 'generacion_id']);
        });

        Schema::dropIfExists('semestres');
    }

    public function down(): void
    {
        Schema::create('semestres', function (Blueprint $table) {
            $table->id();
            $table->tinyInteger('numero')->unique();
            $table->string('nombre', 20);
            $table->timestamps();
        });

        DB::statement("UPDATE alumnos SET semestre_actual_id = CASE semestre_actual_id
            WHEN 'primero' THEN 1 WHEN 'segundo' THEN 2
            WHEN 'tercero' THEN 3 WHEN 'cuarto' THEN 4
            WHEN 'quinto' THEN 5 WHEN 'sexto' THEN 6
        END");

        DB::statement("UPDATE materias SET semestre_id = CASE semestre_id
            WHEN 'primero' THEN 1 WHEN 'segundo' THEN 2
            WHEN 'tercero' THEN 3 WHEN 'cuarto' THEN 4
            WHEN 'quinto' THEN 5 WHEN 'sexto' THEN 6
        END");

        DB::statement("UPDATE grupos SET semestre_id = CASE semestre_id
            WHEN 'primero' THEN 1 WHEN 'segundo' THEN 2
            WHEN 'tercero' THEN 3 WHEN 'cuarto' THEN 4
            WHEN 'quinto' THEN 5 WHEN 'sexto' THEN 6
        END");

        Schema::table('alumnos', function (Blueprint $table) {
            $table->unsignedBigInteger('semestre_actual_id')->change();
            $table->foreign('semestre_actual_id')->references('id')->on('semestres');
        });

        Schema::table('materias', function (Blueprint $table) {
            $table->unsignedBigInteger('semestre_id')->change();
            $table->foreign('semestre_id')->references('id')->on('semestres');
        });

        Schema::table('grupos', function (Blueprint $table) {
            $table->unsignedBigInteger('semestre_id')->change();
            $table->foreign('semestre_id')->references('id')->on('semestres');
            $table->unique(['nombre', 'semestre_id', 'carrera_id', 'generacion_id']);
        });
    }
};

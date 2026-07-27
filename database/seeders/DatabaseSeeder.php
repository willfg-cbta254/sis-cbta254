<?php

namespace Database\Seeders;

use App\Models\BlogPost;
use App\Models\Carrera;
use App\Models\Generacion;
use App\Models\PeriodoEscolar;
use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call(RoleAndPermissionSeeder::class);

        // ========== USUARIO ADMIN ==========
        $adminUser = User::firstOrCreate(
            ['email' => 'admin@cbta254.edu.mx'],
            [
                'name' => 'Administrador del Sistema',
                'password' => bcrypt('admin123'),
            ]
        );
        $adminUser->assignRole('Administrador');

        // ========== CARRERAS ==========
        $carreras = [
            ['clave' => 'BAC-TEC', 'nombre' => 'Bachillerato Tecnológico'],
            ['clave' => 'TEC-OFIM', 'nombre' => 'Técnico en Ofimática'],
            ['clave' => 'TEC-AGRI-SUS', 'nombre' => 'Técnico en Agricultura Sustentable'],
            ['clave' => 'TEC-AGRO', 'nombre' => 'Técnico Agropecuario'],
            ['clave' => 'TEC-ADM-EMP', 'nombre' => 'Técnico en Administración de Emprendimiento'],
        ];
        foreach ($carreras as $carrera) {
            Carrera::create($carrera);
        }

        // ========== PERIODOS ESCOLARES ==========
        PeriodoEscolar::create([
            'clave' => '2026-A',
            'fecha_inicio' => '2026-02-01',
            'fecha_fin' => '2026-07-15',
            'activo' => true,
        ]);
        PeriodoEscolar::create([
            'clave' => '2026-B',
            'fecha_inicio' => '2026-08-01',
            'fecha_fin' => '2027-01-15',
            'activo' => false,
        ]);

        // ========== GENERACION ==========
        $gen = Generacion::create([
            'clave' => '2023-2026',
            'anio_inicio' => 2023,
            'anio_fin' => 2026,
        ]);

        // ========== BLOG POSTS ==========
        BlogPost::factory()->count(5)->published()->create([
            'author_id' => $adminUser->id,
        ]);
        BlogPost::factory()->count(2)->create([
            'author_id' => $adminUser->id,
        ]);
    }
}

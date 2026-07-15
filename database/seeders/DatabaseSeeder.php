<?php

namespace Database\Seeders;

use App\Models\BlogPost;
use App\Models\Carrera;
use App\Models\Generacion;
use App\Models\PeriodoEscolar;
use App\Models\User;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // ========== PERMISOS ==========
        $permissions = [
            ['name' => 'ver faltas', 'guard_name' => 'web'],
            ['name' => 'crear faltas', 'guard_name' => 'web'],
            ['name' => 'editar faltas', 'guard_name' => 'web'],
            ['name' => 'eliminar faltas', 'guard_name' => 'web'],
            ['name' => 'ver horas labor social', 'guard_name' => 'web'],
            ['name' => 'crear horas labor social', 'guard_name' => 'web'],
            ['name' => 'editar horas labor social', 'guard_name' => 'web'],
            ['name' => 'eliminar horas labor social', 'guard_name' => 'web'],
            ['name' => 'ver periodos', 'guard_name' => 'web'],
            ['name' => 'crear periodos', 'guard_name' => 'web'],
            ['name' => 'editar periodos', 'guard_name' => 'web'],
            ['name' => 'eliminar periodos', 'guard_name' => 'web'],
            ['name' => 'ver generaciones', 'guard_name' => 'web'],
            ['name' => 'crear generaciones', 'guard_name' => 'web'],
            ['name' => 'editar generaciones', 'guard_name' => 'web'],
            ['name' => 'eliminar generaciones', 'guard_name' => 'web'],
            ['name' => 'ver semestres', 'guard_name' => 'web'],
            ['name' => 'crear semestres', 'guard_name' => 'web'],
            ['name' => 'editar semestres', 'guard_name' => 'web'],
            ['name' => 'eliminar semestres', 'guard_name' => 'web'],
            ['name' => 'ver materias', 'guard_name' => 'web'],
            ['name' => 'crear materias', 'guard_name' => 'web'],
            ['name' => 'editar materias', 'guard_name' => 'web'],
            ['name' => 'eliminar materias', 'guard_name' => 'web'],
            ['name' => 'ver carreras', 'guard_name' => 'web'],
            ['name' => 'crear carreras', 'guard_name' => 'web'],
            ['name' => 'editar carreras', 'guard_name' => 'web'],
            ['name' => 'eliminar carreras', 'guard_name' => 'web'],
            ['name' => 'ver grupos', 'guard_name' => 'web'],
            ['name' => 'crear grupos', 'guard_name' => 'web'],
            ['name' => 'editar grupos', 'guard_name' => 'web'],
            ['name' => 'eliminar grupos', 'guard_name' => 'web'],
            ['name' => 'ver usuarios', 'guard_name' => 'web'],
            ['name' => 'crear usuarios', 'guard_name' => 'web'],
            ['name' => 'editar usuarios', 'guard_name' => 'web'],
            ['name' => 'eliminar usuarios', 'guard_name' => 'web'],
            ['name' => 'ver profesores', 'guard_name' => 'web'],
            ['name' => 'crear profesores', 'guard_name' => 'web'],
            ['name' => 'editar profesores', 'guard_name' => 'web'],
            ['name' => 'eliminar profesores', 'guard_name' => 'web'],
            ['name' => 'ver alumnos', 'guard_name' => 'web'],
            ['name' => 'crear alumnos', 'guard_name' => 'web'],
            ['name' => 'editar alumnos', 'guard_name' => 'web'],
            ['name' => 'eliminar alumnos', 'guard_name' => 'web'],
            ['name' => 'ver inscripciones', 'guard_name' => 'web'],
            ['name' => 'crear inscripciones', 'guard_name' => 'web'],
            ['name' => 'editar inscripciones', 'guard_name' => 'web'],
            ['name' => 'ver asistencias', 'guard_name' => 'web'],
            ['name' => 'crear asistencias', 'guard_name' => 'web'],
            ['name' => 'editar asistencias', 'guard_name' => 'web'],
            ['name' => 'ver calificaciones', 'guard_name' => 'web'],
            ['name' => 'crear calificaciones', 'guard_name' => 'web'],
            ['name' => 'editar calificaciones', 'guard_name' => 'web'],
            ['name' => 'ver blog', 'guard_name' => 'web'],
            ['name' => 'crear blog', 'guard_name' => 'web'],
            ['name' => 'editar blog', 'guard_name' => 'web'],
            ['name' => 'eliminar blog', 'guard_name' => 'web'],
            ['name' => 'ver conceptos_pago', 'guard_name' => 'web'],
            ['name' => 'crear conceptos_pago', 'guard_name' => 'web'],
            ['name' => 'editar conceptos_pago', 'guard_name' => 'web'],
            ['name' => 'eliminar conceptos_pago', 'guard_name' => 'web'],
            ['name' => 'ver pagos', 'guard_name' => 'web'],
            ['name' => 'crear pagos', 'guard_name' => 'web'],
            ['name' => 'editar pagos', 'guard_name' => 'web'],
            ['name' => 'eliminar pagos', 'guard_name' => 'web'],
            ['name' => 'ver horarios', 'guard_name' => 'web'],
            ['name' => 'crear horarios', 'guard_name' => 'web'],
            ['name' => 'editar horarios', 'guard_name' => 'web'],
            ['name' => 'eliminar horarios', 'guard_name' => 'web'],
        ];

        foreach ($permissions as $permiso) {
            Permission::firstOrCreate($permiso);
        }

        $admin = Role::firstOrCreate(['name' => 'Administrador', 'guard_name' => 'web']);
        $admin->givePermissionTo(Permission::all());

        $profesor = Role::firstOrCreate(['name' => 'Profesor', 'guard_name' => 'web']);
        $profesor->givePermissionTo([
            'ver materias', 'ver grupos', 'ver alumnos',
            'ver asistencias', 'crear asistencias', 'editar asistencias',
            'ver calificaciones', 'crear calificaciones', 'editar calificaciones',
        ]);

        $alumno = Role::firstOrCreate(['name' => 'Alumno', 'guard_name' => 'web']);

        $prefecto = Role::firstOrCreate(['name' => 'Prefecto', 'guard_name' => 'web']);
        $prefecto->givePermissionTo([
            'ver faltas', 'crear faltas', 'editar faltas', 'eliminar faltas',
            'ver horas labor social', 'crear horas labor social', 'editar horas labor social', 'eliminar horas labor social',
        ]);

        $redesSociales = Role::firstOrCreate(['name' => 'Administrador de Redes Sociales', 'guard_name' => 'web']);
        $redesSociales->givePermissionTo([
            'ver blog', 'crear blog', 'editar blog', 'eliminar blog',
        ]);

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

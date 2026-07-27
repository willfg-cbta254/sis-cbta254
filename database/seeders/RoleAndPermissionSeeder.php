<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RoleAndPermissionSeeder extends Seeder
{
    public function run(): void
    {
        $this->createPermissions();
        $this->createRoles();
    }

    private function createPermissions(): void
    {
        $entities = [
            'periodos', 'generaciones', 'carreras', 'materias', 'grupos',
            'usuarios', 'profesores', 'alumnos', 'inscripciones', 'asistencias',
            'calificaciones', 'blog', 'faltas', 'horas labor social',
            'conceptos_pago', 'pagos', 'horarios', 'cargas academicas',
            'estrategias didacticas',
        ];

        foreach ($entities as $entity) {
            foreach (['ver', 'crear', 'editar', 'eliminar'] as $action) {
                Permission::firstOrCreate([
                    'name' => "{$action} {$entity}",
                    'guard_name' => 'web',
                ]);
            }
        }

        Permission::firstOrCreate(['name' => 'ver corte de caja', 'guard_name' => 'web']);
    }

    private function createRoles(): void
    {
        $admin = Role::firstOrCreate(['name' => 'Administrador', 'guard_name' => 'web']);
        $admin->syncPermissions(Permission::all());

        $profesor = Role::firstOrCreate(['name' => 'Profesor', 'guard_name' => 'web']);
        $profesor->syncPermissions([
            'ver materias', 'ver grupos', 'ver alumnos',
            'ver asistencias', 'crear asistencias', 'editar asistencias',
            'ver calificaciones', 'crear calificaciones', 'editar calificaciones',
        ]);

        Role::firstOrCreate(['name' => 'Alumno', 'guard_name' => 'web'])->syncPermissions([]);

        $prefecto = Role::firstOrCreate(['name' => 'Prefecto', 'guard_name' => 'web']);
        $prefecto->syncPermissions([
            'ver faltas', 'crear faltas', 'editar faltas', 'eliminar faltas',
            'ver horas labor social', 'crear horas labor social',
            'editar horas labor social', 'eliminar horas labor social',
        ]);

        $redes = Role::firstOrCreate(['name' => 'Administrador de Redes Sociales', 'guard_name' => 'web']);
        $redes->syncPermissions([
            'ver blog', 'crear blog', 'editar blog', 'eliminar blog',
        ]);

        $financiero = Role::firstOrCreate(['name' => 'Financiero', 'guard_name' => 'web']);
        $financiero->syncPermissions([
            'ver conceptos_pago', 'crear conceptos_pago',
            'editar conceptos_pago', 'eliminar conceptos_pago',
            'ver pagos', 'crear pagos', 'editar pagos', 'eliminar pagos',
            'ver corte de caja',
        ]);

        $serviciosEscolares = Role::firstOrCreate(['name' => 'Servicios Escolares', 'guard_name' => 'web']);
        $serviciosEscolares->syncPermissions([
            'ver periodos', 'crear periodos', 'editar periodos', 'eliminar periodos',
            'ver generaciones', 'crear generaciones', 'editar generaciones', 'eliminar generaciones',
            'ver carreras', 'crear carreras', 'editar carreras', 'eliminar carreras',
            'ver materias', 'crear materias', 'editar materias', 'eliminar materias',
            'ver grupos', 'crear grupos', 'editar grupos', 'eliminar grupos',
            'ver alumnos', 'crear alumnos', 'editar alumnos', 'eliminar alumnos',
            'ver profesores', 'crear profesores', 'editar profesores', 'eliminar profesores',
            'ver inscripciones', 'crear inscripciones', 'editar inscripciones',
            'ver horarios', 'crear horarios', 'editar horarios', 'eliminar horarios',
            'ver calificaciones', 'crear calificaciones', 'editar calificaciones',
            'ver pagos',
            'ver cargas academicas', 'crear cargas academicas',
            'editar cargas academicas', 'eliminar cargas academicas',
        ]);
    }
}

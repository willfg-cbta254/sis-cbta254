<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RoleAndPermissionSeeder extends Seeder
{
    public function run(): void
    {
        $rolePermissions = [
            'Profesor' => [
                'ver materias', 'ver grupos', 'ver alumnos',
                'ver asistencias', 'crear asistencias', 'editar asistencias',
                'ver calificaciones', 'crear calificaciones', 'editar calificaciones',
            ],
            'Alumno' => [],
            'Prefecto' => [
                'ver faltas', 'crear faltas', 'editar faltas', 'eliminar faltas',
                'ver horas labor social', 'crear horas labor social',
                'editar horas labor social', 'eliminar horas labor social',
            ],
            'Administrador de Redes Sociales' => [
                'ver blog', 'crear blog', 'editar blog', 'eliminar blog',
            ],
            'Financiero' => [
                'ver conceptos_pago', 'crear conceptos_pago', 'editar conceptos_pago', 'eliminar conceptos_pago',
                'ver pagos', 'crear pagos', 'editar pagos', 'eliminar pagos',
                'ver corte de caja',
            ],
            'Servicios Escolares' => [
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
            ],
        ];

        $this->ensurePermissions(array_unique(array_merge(...array_values($rolePermissions))));

        foreach ($rolePermissions as $name => $permissions) {
            Role::firstOrCreate(['name' => $name, 'guard_name' => 'web'])
                ->syncPermissions($permissions);
        }

        Role::firstOrCreate(['name' => 'Administrador', 'guard_name' => 'web'])
            ->syncPermissions(Permission::all());

        Role::firstOrCreate(['name' => config('filament-shield.super_admin.name'), 'guard_name' => 'web'])
            ->syncPermissions(Permission::all());
    }

    /**
     * @param  array<int, string>  $names
     */
    private function ensurePermissions(array $names): void
    {
        foreach ($names as $name) {
            Permission::firstOrCreate(['name' => $name, 'guard_name' => 'web']);
        }
    }
}

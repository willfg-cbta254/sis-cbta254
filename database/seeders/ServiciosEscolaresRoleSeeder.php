<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class ServiciosEscolaresRoleSeeder extends Seeder
{
    public function run(): void
    {
        $permissions = [
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
            'ver cargas academicas', 'crear cargas academicas', 'editar cargas academicas', 'eliminar cargas academicas',
        ];

        foreach ($permissions as $name) {
            Permission::firstOrCreate(['name' => $name, 'guard_name' => 'web']);
        }

        $role = Role::firstOrCreate(['name' => 'Servicios Escolares', 'guard_name' => 'web']);
        $role->syncPermissions($permissions);
    }
}

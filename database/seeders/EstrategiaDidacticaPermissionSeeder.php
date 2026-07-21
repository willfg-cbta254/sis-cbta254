<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class EstrategiaDidacticaPermissionSeeder extends Seeder
{
    public function run(): void
    {
        $permissions = [
            ['name' => 'ver estrategias didacticas', 'guard_name' => 'web'],
            ['name' => 'crear estrategias didacticas', 'guard_name' => 'web'],
            ['name' => 'editar estrategias didacticas', 'guard_name' => 'web'],
            ['name' => 'eliminar estrategias didacticas', 'guard_name' => 'web'],
        ];

        foreach ($permissions as $permiso) {
            Permission::firstOrCreate($permiso);
        }

        $admin = Role::findByName('Administrador', 'web');
        $admin->givePermissionTo(Permission::all());

        $this->command?->info('Permisos de estrategias didácticas creados correctamente.');
    }
}

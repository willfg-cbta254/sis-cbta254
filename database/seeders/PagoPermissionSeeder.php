<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class PagoPermissionSeeder extends Seeder
{
    public function run(): void
    {
        $permissions = [
            ['name' => 'ver conceptos_pago', 'guard_name' => 'web'],
            ['name' => 'crear conceptos_pago', 'guard_name' => 'web'],
            ['name' => 'editar conceptos_pago', 'guard_name' => 'web'],
            ['name' => 'eliminar conceptos_pago', 'guard_name' => 'web'],
            ['name' => 'ver pagos', 'guard_name' => 'web'],
            ['name' => 'crear pagos', 'guard_name' => 'web'],
            ['name' => 'editar pagos', 'guard_name' => 'web'],
            ['name' => 'eliminar pagos', 'guard_name' => 'web'],
        ];

        foreach ($permissions as $permiso) {
            Permission::firstOrCreate($permiso);
        }

        $admin = Role::firstOrCreate(['name' => 'Administrador', 'guard_name' => 'web']);
        $admin->givePermissionTo(Permission::all());

        $this->command?->info('Permisos de pagos creados correctamente.');
    }
}

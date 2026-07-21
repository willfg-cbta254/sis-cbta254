<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class FinancieroRoleSeeder extends Seeder
{
    public function run(): void
    {
        Permission::firstOrCreate(['name' => 'ver corte de caja', 'guard_name' => 'web']);

        $financiero = Role::firstOrCreate(['name' => 'Financiero', 'guard_name' => 'web']);
        $financiero->givePermissionTo([
            'ver conceptos_pago',
            'crear conceptos_pago',
            'editar conceptos_pago',
            'eliminar conceptos_pago',
            'ver pagos',
            'crear pagos',
            'editar pagos',
            'eliminar pagos',
            'ver corte de caja',
        ]);

        $admin = Role::findByName('Administrador', 'web');
        $admin->givePermissionTo('ver corte de caja');

        $this->command?->info('Rol Financiero y permisos creados correctamente.');
    }
}

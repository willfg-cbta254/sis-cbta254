<?php

namespace App\Console\Commands;

use App\Enums\Semestre;
use App\Models\Carrera;
use App\Models\Generacion;
use App\Models\User;
use Illuminate\Console\Command;
use Spatie\Permission\Models\Role;

class SyncAlumnos extends Command
{
    protected $signature = 'alumnos:sync';

    protected $description = 'Asigna el rol Alumno y crea perfiles para usuarios sin rol';

    public function handle(): void
    {
        $generacion = Generacion::first();
        $carrera = Carrera::first();

        if (! $generacion || ! $carrera) {
            $this->error('Debe existir al menos una generación y una carrera registradas.');

            return;
        }

        $role = Role::firstOrCreate(['name' => 'Alumno', 'guard_name' => 'web']);

        $users = User::doesntHave('roles')->get();

        if ($users->isEmpty()) {
            $this->info('No hay usuarios sin rol para convertir.');

            return;
        }

        $bar = $this->output->createProgressBar($users->count());
        $bar->start();

        foreach ($users as $user) {
            $user->assignRole($role);

            if (! $user->alumno()->exists()) {
                $user->alumno()->create([
                    'matricula' => 'AUTO-'.$user->id,
                    'generacion_id' => $generacion->id,
                    'carrera_id' => $carrera->id,
                    'semestre_actual_id' => Semestre::Primero->value,
                    'fecha_ingreso' => now()->toDateString(),
                ]);
            }

            $bar->advance();
        }

        $bar->finish();
        $this->newLine();
        $this->info("{$users->count()} usuarios convertidos a Alumnos correctamente.");
    }
}

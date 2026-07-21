<?php

namespace App\Models;

use App\Enums\Semestre;
use Database\Factories\UserFactory;
use Filament\Models\Contracts\FilamentUser;
use Filament\Models\Contracts\HasAvatar;
use Filament\Panel;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Storage;
use Spatie\Permission\Traits\HasRoles;

#[Fillable(['name', 'email', 'password', 'curp', 'telefono', 'fecha_nacimiento', 'direccion', 'activo', 'avatar_url'])]
#[Hidden(['password', 'remember_token'])]
class User extends Authenticatable implements FilamentUser, HasAvatar
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, HasRoles, Notifiable;

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'fecha_nacimiento' => 'date',
            'activo' => 'boolean',
        ];
    }

    public function getFilamentAvatarUrl(): ?string
    {
        return $this->avatar_url ? Storage::url($this->avatar_url) : null;
    }

    public function syncProfileFromRoles(): static
    {
        $this->load('roles');

        if ($this->hasRole('Alumno') && ! $this->alumno()->exists()) {
            $this->alumno()->create([
                'matricula' => 'AUTO-'.$this->id,
                'generacion_id' => Generacion::first()?->id ?? throw new \RuntimeException('No hay generaciones registradas. Crea una antes de asignar el rol Alumno.'),
                'carrera_id' => Carrera::first()?->id ?? throw new \RuntimeException('No hay carreras registradas. Crea una antes de asignar el rol Alumno.'),
                'semestre_actual_id' => Semestre::Primero->value,
                'fecha_ingreso' => now()->toDateString(),
            ]);
        }

        if ($this->hasRole('Profesor') && ! $this->profesor()->exists()) {
            $this->profesor()->create([
                'numero_empleado' => 'AUTO-'.$this->id,
                'fecha_ingreso' => now()->toDateString(),
            ]);
        }

        return $this;
    }

    public function canAccessPanel(Panel $panel): bool
    {
        return match ($panel->getId()) {
            'admin' => $this->hasRole('Administrador'),
            'financiero' => $this->hasAnyRole(['Administrador', 'Financiero']),
            'profesor' => $this->hasRole('Profesor') && $this->profesor()->exists(),
            'alumno' => $this->hasRole('Alumno') && $this->alumno()->exists(),
            'servicios-escolares' => $this->hasRole('Servicios Escolares'),
            default => false,
        };
    }

    public function profesor(): HasOne
    {
        return $this->hasOne(Profesor::class);
    }

    public function alumno(): HasOne
    {
        return $this->hasOne(Alumno::class);
    }
}

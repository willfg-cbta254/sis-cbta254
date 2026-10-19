<?php

namespace App\Models;

use App\Enums\Semestre;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class AreaPropedeutica extends Model
{
    protected $table = 'areas_propedeuticas';

    protected $fillable = ['clave', 'nombre', 'descripcion', 'semestre', 'capacidad', 'activo'];

    protected function casts(): array
    {
        return [
            'semestre' => Semestre::class,
            'capacidad' => 'integer',
            'activo' => 'boolean',
        ];
    }

    public function materias(): HasMany
    {
        return $this->hasMany(Materia::class);
    }

    public function selecciones(): HasMany
    {
        return $this->hasMany(AlumnoAreaPropedeutica::class);
    }

    public function alumnos(): BelongsToMany
    {
        return $this->belongsToMany(Alumno::class, 'alumno_area_propedeutica')
            ->withPivot('periodo_escolar_id')
            ->withTimestamps();
    }

    public function inscritosCount(): int
    {
        return $this->selecciones()->count();
    }

    public function lugaresDisponibles(): int
    {
        return max(0, $this->capacidad - $this->inscritosCount());
    }

    public function tieneCupo(): bool
    {
        return $this->lugaresDisponibles() > 0;
    }
}

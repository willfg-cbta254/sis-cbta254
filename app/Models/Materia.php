<?php

namespace App\Models;

use App\Enums\Semestre;
use App\Enums\TipoMateria;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Materia extends Model
{
    protected $fillable = ['nombre', 'clave', 'tipo', 'semestre_id', 'area_propedeutica_id', 'horas_semana'];

    protected function casts(): array
    {
        return [
            'semestre_id' => Semestre::class,
            'tipo' => TipoMateria::class,
        ];
    }

    public function profesores(): BelongsToMany
    {
        return $this->belongsToMany(Profesor::class, 'carga_academica', 'materia_id', 'profesor_id')
            ->distinct();
    }

    public function grupos(): BelongsToMany
    {
        return $this->belongsToMany(Grupo::class, 'carga_academica')
            ->withPivot(['profesor_id', 'periodo_escolar_id'])
            ->withTimestamps();
    }

    public function cargaAcademica(): HasMany
    {
        return $this->hasMany(CargaAcademica::class);
    }

    public function asistencias(): HasMany
    {
        return $this->hasMany(Asistencia::class);
    }

    public function calificaciones(): HasMany
    {
        return $this->hasMany(Calificacion::class);
    }

    public function areaPropedeutica(): BelongsTo
    {
        return $this->belongsTo(AreaPropedeutica::class);
    }

    public function esOptativa(): bool
    {
        return $this->area_propedeutica_id !== null;
    }
}

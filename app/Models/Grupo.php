<?php

namespace App\Models;

use App\Enums\Semestre;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Grupo extends Model
{
    protected $fillable = ['nombre', 'semestre_id', 'carrera_id', 'generacion_id', 'turno', 'capacidad', 'activo'];

    protected function casts(): array
    {
        return [
            'activo' => 'boolean',
            'semestre_id' => Semestre::class,
        ];
    }

    public function carrera(): BelongsTo
    {
        return $this->belongsTo(Carrera::class);
    }

    public function generacion(): BelongsTo
    {
        return $this->belongsTo(Generacion::class);
    }

    public function inscripciones(): HasMany
    {
        return $this->hasMany(Inscripcion::class);
    }

    public function alumnos(): BelongsToMany
    {
        return $this->belongsToMany(Alumno::class, 'inscripciones')
            ->withPivot(['periodo_escolar_id', 'fecha_inscripcion', 'status'])
            ->withTimestamps();
    }

    public function profesores(): BelongsToMany
    {
        return $this->belongsToMany(Profesor::class, 'carga_academica')
            ->withPivot(['materia_id', 'periodo_escolar_id'])
            ->withTimestamps();
    }

    public function cargaAcademica(): HasMany
    {
        return $this->hasMany(CargaAcademica::class);
    }
}

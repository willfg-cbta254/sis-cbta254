<?php

namespace App\Models;

use App\Enums\Semestre;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Alumno extends Model
{
    protected $fillable = ['user_id', 'matricula', 'generacion_id', 'carrera_id', 'semestre_actual_id', 'fecha_ingreso'];

    protected function casts(): array
    {
        return [
            'fecha_ingreso' => 'date',
            'semestre_actual_id' => Semestre::class,
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function generacion(): BelongsTo
    {
        return $this->belongsTo(Generacion::class);
    }

    public function carrera(): BelongsTo
    {
        return $this->belongsTo(Carrera::class);
    }

    public function inscripciones(): HasMany
    {
        return $this->hasMany(Inscripcion::class);
    }

    public function grupos(): BelongsToMany
    {
        return $this->belongsToMany(Grupo::class, 'inscripciones')
            ->withPivot(['periodo_escolar_id', 'fecha_inscripcion', 'status'])
            ->withTimestamps();
    }

    public function asistencias(): HasMany
    {
        return $this->hasMany(Asistencia::class);
    }

    public function calificaciones(): HasMany
    {
        return $this->hasMany(Calificacion::class);
    }

    public function materias(): BelongsToMany
    {
        return $this->belongsToMany(Materia::class, 'calificaciones', 'alumno_id', 'materia_id')
            ->distinct();
    }

    public function pagos(): HasMany
    {
        return $this->hasMany(Pago::class);
    }

    public function horasLaborSocial(): HasMany
    {
        return $this->hasMany(HoraLaborSocial::class);
    }
}

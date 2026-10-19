<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class TutoriaIncidencia extends Model
{
    protected $fillable = [
        'grupo_id',
        'tutor_id',
        'tipo',
        'descripcion',
        'periodo_escolar_id',
        'registrado_por_id',
        'alumnos_ids',
    ];

    protected function casts(): array
    {
        return [
            'alumnos_ids' => 'array',
        ];
    }

    public function grupo(): BelongsTo
    {
        return $this->belongsTo(Grupo::class);
    }

    public function tutor(): BelongsTo
    {
        return $this->belongsTo(Profesor::class, 'tutor_id');
    }

    public function registradoPor(): BelongsTo
    {
        return $this->belongsTo(Profesor::class, 'registrado_por_id');
    }

    public function periodoEscolar(): BelongsTo
    {
        return $this->belongsTo(PeriodoEscolar::class);
    }

    public function alumnos(): BelongsToMany
    {
        return $this->belongsToMany(Alumno::class, 'tutoria_incidencia_alumnos')
            ->withTimestamps();
    }
}

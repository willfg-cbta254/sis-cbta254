<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TutoriaIncidenciaAlumno extends Model
{
    protected $fillable = [
        'tutoria_incidencia_id',
        'alumno_id',
    ];

    public function incidencia(): BelongsTo
    {
        return $this->belongsTo(TutoriaIncidencia::class, 'tutoria_incidencia_id');
    }

    public function alumno(): BelongsTo
    {
        return $this->belongsTo(Alumno::class);
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Calificacion extends Model
{
    protected $table = 'calificaciones';

    protected $fillable = [
        'alumno_id', 'materia_id', 'profesor_id', 'grupo_id', 'periodo_escolar_id',
        'parcial1', 'parcial2', 'parcial3', 'promedio',
        'extraordinario', 'curso_intersemestral', 'recursamiento',
    ];

    protected function casts(): array
    {
        return [
            'parcial1' => 'decimal:2',
            'parcial2' => 'decimal:2',
            'parcial3' => 'decimal:2',
            'promedio' => 'decimal:2',
            'extraordinario' => 'decimal:2',
            'curso_intersemestral' => 'decimal:2',
            'recursamiento' => 'boolean',
        ];
    }

    protected static function booted(): void
    {
        static::saving(function (self $calificacion) {
            $p1 = $calificacion->parcial1 ?? 0;
            $p2 = $calificacion->parcial2 ?? 0;
            $p3 = $calificacion->parcial3 ?? 0;
            $calificacion->promedio = round(($p1 + $p2 + $p3) / 3, 2);

            if ($calificacion->curso_intersemestral !== null && $calificacion->curso_intersemestral < 6) {
                $calificacion->recursamiento = true;
            }
        });
    }

    public function alumno(): BelongsTo
    {
        return $this->belongsTo(Alumno::class);
    }

    public function materia(): BelongsTo
    {
        return $this->belongsTo(Materia::class);
    }

    public function profesor(): BelongsTo
    {
        return $this->belongsTo(Profesor::class);
    }

    public function grupo(): BelongsTo
    {
        return $this->belongsTo(Grupo::class);
    }

    public function periodoEscolar(): BelongsTo
    {
        return $this->belongsTo(PeriodoEscolar::class);
    }
}

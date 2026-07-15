<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class HoraLaborSocial extends Model
{
    protected $table = 'horas_labor_social';

    protected $fillable = [
        'alumno_id',
        'falta_reglamentaria_id',
        'horas_asignadas',
        'horas_cumplidas',
        'fecha_asignacion',
        'estado',
        'observaciones',
        'registrado_por',
    ];

    protected function casts(): array
    {
        return [
            'horas_asignadas' => 'decimal:1',
            'horas_cumplidas' => 'decimal:1',
            'fecha_asignacion' => 'date',
        ];
    }

    public function alumno(): BelongsTo
    {
        return $this->belongsTo(Alumno::class);
    }

    public function faltaReglamentaria(): BelongsTo
    {
        return $this->belongsTo(FaltasReglamentaria::class);
    }

    public function registradoPor(): BelongsTo
    {
        return $this->belongsTo(User::class, 'registrado_por');
    }
}

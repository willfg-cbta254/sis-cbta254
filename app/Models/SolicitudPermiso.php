<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SolicitudPermiso extends Model
{
    protected $fillable = [
        'profesor_id',
        'fecha_inicio',
        'fecha_fin',
        'motivo',
        'estado',
        'respuesta',
        'respondido_por_id',
        'respondido_en',
    ];

    protected function casts(): array
    {
        return [
            'fecha_inicio' => 'date',
            'fecha_fin' => 'date',
            'dias_solicitados' => 'integer',
            'respondido_en' => 'datetime',
        ];
    }

    protected static function booted(): void
    {
        static::saving(function (SolicitudPermiso $solicitud): void {
            if ($solicitud->fecha_inicio && $solicitud->fecha_fin) {
                $solicitud->dias_solicitados = max(1, (int) $solicitud->fecha_inicio->diffInDays($solicitud->fecha_fin) + 1);
            }
        });
    }

    public function profesor(): BelongsTo
    {
        return $this->belongsTo(Profesor::class);
    }

    public function respondidoPor(): BelongsTo
    {
        return $this->belongsTo(User::class, 'respondido_por_id');
    }

    public function scopePendiente(Builder $query): void
    {
        $query->where('estado', 'pendiente');
    }
}

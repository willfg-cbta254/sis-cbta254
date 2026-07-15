<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ConceptoPago extends Model
{
    protected $table = 'conceptos_pagos';

    protected $fillable = [
        'nombre',
        'descripcion',
        'monto',
        'activo',
    ];

    protected function casts(): array
    {
        return [
            'monto' => 'decimal:2',
            'activo' => 'boolean',
        ];
    }

    public function pagos(): HasMany
    {
        return $this->hasMany(Pago::class);
    }
}

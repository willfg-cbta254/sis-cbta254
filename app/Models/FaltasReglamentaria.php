<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class FaltasReglamentaria extends Model
{
    protected $fillable = ['nombre', 'descripcion', 'horas_asignadas', 'activo'];

    protected function casts(): array
    {
        return [
            'horas_asignadas' => 'decimal:1',
            'activo' => 'boolean',
        ];
    }

    public function horasLaborSocial(): HasMany
    {
        return $this->hasMany(HoraLaborSocial::class, 'falta_reglamentaria_id');
    }
}

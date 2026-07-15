<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Generacion extends Model
{
    protected $table = 'generaciones';

    protected $fillable = ['clave', 'anio_inicio', 'anio_fin'];

    public function alumnos(): HasMany
    {
        return $this->hasMany(Alumno::class);
    }

    public function grupos(): HasMany
    {
        return $this->hasMany(Grupo::class);
    }
}

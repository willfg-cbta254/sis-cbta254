<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Ficha extends Model
{
    protected $fillable = [
        'folio',
        'folio_num',
        'ciclo_escolar',
        'nombre_completo',
        'fecha_nacimiento',
        'edad',
        'genero',
        'direccion',
        'num_celular',
        'correo_electronico',
        'localidad',
        'municipio',
        'secundaria_procedencia',
        'tipo_secundaria',
        'localidad_secundaria',
        'turno',
        'promedio',
        'tutor_nombre_completo',
        'tutor_num_celular',
        'tutor_correo_electronico',
    ];

    protected function casts(): array
    {
        return [
            'fecha_nacimiento' => 'date',
            'promedio' => 'decimal:2',
        ];
    }
}

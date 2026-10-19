<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AlumnoAreaPropedeutica extends Model
{
    protected $table = 'alumno_area_propedeutica';

    protected $fillable = ['alumno_id', 'area_propedeutica_id', 'periodo_escolar_id'];

    public function alumno(): BelongsTo
    {
        return $this->belongsTo(Alumno::class);
    }

    public function areaPropedeutica(): BelongsTo
    {
        return $this->belongsTo(AreaPropedeutica::class);
    }

    public function periodoEscolar(): BelongsTo
    {
        return $this->belongsTo(PeriodoEscolar::class);
    }
}

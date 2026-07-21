<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class EstrategiaDidactica extends Model
{
    protected $table = 'estrategias_didacticas';

    protected $fillable = [
        'profesor_id',
        'materia_id',
        'periodo_escolar_id',
        'titulo',
        'descripcion',
        'archivo',
    ];

    public function profesor(): BelongsTo
    {
        return $this->belongsTo(Profesor::class);
    }

    public function materia(): BelongsTo
    {
        return $this->belongsTo(Materia::class);
    }

    public function periodoEscolar(): BelongsTo
    {
        return $this->belongsTo(PeriodoEscolar::class);
    }
}

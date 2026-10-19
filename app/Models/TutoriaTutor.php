<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TutoriaTutor extends Model
{
    protected $fillable = ['profesor_id', 'activo', 'observaciones'];

    protected function casts(): array
    {
        return [
            'activo' => 'boolean',
        ];
    }

    public function profesor(): BelongsTo
    {
        return $this->belongsTo(Profesor::class);
    }
}

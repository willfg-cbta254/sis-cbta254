<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FichaSetting extends Model
{
    protected $fillable = ['is_open', 'ciclo_escolar', 'current_folio'];

    protected function casts(): array
    {
        return [
            'is_open' => 'boolean',
        ];
    }
}

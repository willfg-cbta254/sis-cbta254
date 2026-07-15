<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Announcement extends Model
{
    protected $fillable = [
        'title',
        'content',
        'type',
        'is_published',
        'published_at',
    ];

    protected function casts(): array
    {
        return [
            'is_published' => 'boolean',
            'published_at' => 'datetime',
        ];
    }

    public function grupos(): BelongsToMany
    {
        return $this->belongsToMany(Grupo::class, 'announcement_grupo');
    }

    public function alumnos(): BelongsToMany
    {
        return $this->belongsToMany(Alumno::class, 'announcement_alumno');
    }

    public function scopePublished($query)
    {
        return $query->where('is_published', true)
            ->where('published_at', '<=', now());
    }

    public function scopeForStudent($query, Alumno $alumno)
    {
        $grupoIds = $alumno->grupos()->pluck('grupos.id');

        return $query->published()
            ->where(function ($q) use ($alumno, $grupoIds) {
                $q->where('type', 'community')
                    ->orWhere(function ($q) use ($grupoIds) {
                        $q->where('type', 'group')
                            ->whereHas('grupos', fn ($q) => $q->whereIn('grupos.id', $grupoIds));
                    })
                    ->orWhere(function ($q) use ($alumno) {
                        $q->where('type', 'student')
                            ->whereHas('alumnos', fn ($q) => $q->where('alumnos.id', $alumno->id));
                    });
            });
    }
}

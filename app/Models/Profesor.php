<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Profesor extends Model
{
    protected $table = 'profesores';

    protected $fillable = ['user_id', 'numero_empleado', 'fecha_ingreso', 'especialidad', 'horas_semanales'];

    protected function casts(): array
    {
        return ['fecha_ingreso' => 'date'];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function materias(): BelongsToMany
    {
        return $this->belongsToMany(Materia::class, 'carga_academica', 'profesor_id', 'materia_id')
            ->distinct();
    }

    public function grupos(): BelongsToMany
    {
        return $this->belongsToMany(Grupo::class, 'carga_academica')
            ->withPivot(['materia_id', 'periodo_escolar_id'])
            ->withTimestamps();
    }

    public function cargaAcademica(): HasMany
    {
        return $this->hasMany(CargaAcademica::class);
    }

    public function grupoTutor(): HasOne
    {
        return $this->hasOne(Grupo::class, 'tutor_id');
    }

    public function tutoriaTutor(): HasOne
    {
        return $this->hasOne(TutoriaTutor::class);
    }

    public function tutoriaIncidenciasRegistradas(): HasMany
    {
        return $this->hasMany(TutoriaIncidencia::class, 'registrado_por_id');
    }

    public function tutoriaIncidenciasComoTutor(): HasMany
    {
        return $this->hasMany(TutoriaIncidencia::class, 'tutor_id');
    }

    public function asistencias(): HasMany
    {
        return $this->hasMany(Asistencia::class);
    }

    public function calificaciones(): HasMany
    {
        return $this->hasMany(Calificacion::class);
    }

    public function estrategiasDidacticas(): HasMany
    {
        return $this->hasMany(EstrategiaDidactica::class);
    }
}

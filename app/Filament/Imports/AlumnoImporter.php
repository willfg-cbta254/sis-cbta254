<?php

namespace App\Filament\Imports;

use App\Enums\Semestre;
use App\Models\Alumno;
use App\Models\Carrera;
use App\Models\Generacion;
use App\Models\Grupo;
use App\Models\Inscripcion;
use App\Models\PeriodoEscolar;
use App\Models\User;
use Carbon\Carbon;
use Filament\Actions\Imports\Exceptions\RowImportFailedException;
use Filament\Actions\Imports\ImportColumn;
use Filament\Actions\Imports\Importer;
use Filament\Actions\Imports\Models\Import;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Number;

class AlumnoImporter extends Importer
{
    protected static ?string $model = Alumno::class;

    public static function getColumns(): array
    {
        return [
            ImportColumn::make('email')
                ->label('Correo electrónico')
                ->requiredMapping()
                ->rules(['required', 'email'])
                ->example('alumno@cbta254.edu.mx')
                ->fillRecordUsing(fn ($record, $state): null => null),
            ImportColumn::make('name')
                ->label('Nombre completo')
                ->requiredMapping()
                ->rules(['required', 'string', 'max:255'])
                ->example('Juan Pérez López')
                ->fillRecordUsing(fn ($record, $state): null => null),
            ImportColumn::make('password')
                ->label('Contraseña')
                ->rules(['nullable', 'string', 'min:8'])
                ->example('password123')
                ->fillRecordUsing(fn ($record, $state): null => null),
            ImportColumn::make('curp')
                ->label('CURP')
                ->rules(['nullable', 'string', 'max:18'])
                ->example('AAGJ831208HPLLLS02')
                ->fillRecordUsing(fn ($record, $state): null => null),
            ImportColumn::make('telefono')
                ->label('Teléfono')
                ->rules(['nullable', 'string', 'max:20'])
                ->example('2221234567')
                ->fillRecordUsing(fn ($record, $state): null => null),
            ImportColumn::make('fecha_nacimiento')
                ->label('Fecha de nacimiento')
                ->rules(['nullable', 'date'])
                ->castStateUsing(fn ($state): ?string => static::parseDate($state))
                ->example('15/03/2008')
                ->fillRecordUsing(fn ($record, $state): null => null),
            ImportColumn::make('direccion')
                ->label('Dirección')
                ->rules(['nullable', 'string', 'max:255'])
                ->example('Calle Falsa 123, Puebla')
                ->fillRecordUsing(fn ($record, $state): null => null),
            ImportColumn::make('matricula')
                ->requiredMapping()
                ->label('Matrícula')
                ->rules(['required'])
                ->example('2026-0001')
                ->fillRecordUsing(fn (): null => null),
            ImportColumn::make('generacion')
                ->requiredMapping()
                ->relationship(resolveUsing: ['clave', 'id'])
                ->label('Generación')
                ->rules(['required'])
                ->example('2026-2029')
                ->fillRecordUsing(fn (): null => null),
            ImportColumn::make('carrera')
                ->requiredMapping()
                ->relationship(resolveUsing: ['nombre', 'id'])
                ->label('Carrera')
                ->rules(['required'])
                ->example('Bachillerato Tecnológico')
                ->fillRecordUsing(fn (): null => null),
            ImportColumn::make('semestre_actual_id')
                ->requiredMapping()
                ->label('Semestre actual')
                ->rules(['required', 'string', 'in:'.implode(',', array_column(Semestre::cases(), 'value'))])
                ->castStateUsing(fn ($state): ?string => Semestre::normalize($state))
                ->example('Primero')
                ->fillRecordUsing(fn (): null => null),
            ImportColumn::make('fecha_ingreso')
                ->requiredMapping()
                ->label('Fecha de ingreso')
                ->rules(['required', 'date'])
                ->castStateUsing(fn ($state): ?string => static::parseDate($state))
                ->example('26/08/2026')
                ->fillRecordUsing(fn (): null => null),
            ImportColumn::make('grupo')
                ->requiredMapping()
                ->label('Grupo')
                ->rules(['required'])
                ->example('1 "A"')
                ->fillRecordUsing(fn (): null => null),
        ];
    }

    public function resolveRecord(): ?Alumno
    {
        $email = $this->data['email'] ?? null;

        if (blank($email)) {
            return null;
        }

        $user = User::firstOrCreate(
            ['email' => $email],
            [
                'name' => $this->data['name'] ?? $email,
                'password' => Hash::make($this->data['password'] ?? 'password'),
                'curp' => $this->data['curp'] ?? null,
                'telefono' => $this->data['telefono'] ?? null,
                'fecha_nacimiento' => $this->data['fecha_nacimiento'] ?? null,
                'direccion' => $this->data['direccion'] ?? null,
            ]
        );

        if ($user->wasRecentlyCreated === false) {
            $user->update([
                'name' => $this->data['name'] ?? $user->name,
                'curp' => $this->data['curp'] ?? $user->curp,
                'telefono' => $this->data['telefono'] ?? $user->telefono,
                'fecha_nacimiento' => $this->data['fecha_nacimiento'] ?? $user->fecha_nacimiento,
                'direccion' => $this->data['direccion'] ?? $user->direccion,
            ]);
        }

        $user->assignRole('Alumno');

        $alumno = Alumno::firstOrNew(['user_id' => $user->id]);

        $alumno->matricula = $this->data['matricula'];

        $generacion = Generacion::where('clave', $this->data['generacion'])
            ->orWhere('id', $this->data['generacion'])
            ->first();

        if ($generacion === null) {
            throw new RowImportFailedException("La generación '{$this->data['generacion']}' no existe.");
        }

        $alumno->generacion_id = $generacion->id;

        $carrera = Carrera::where('nombre', $this->data['carrera'])
            ->orWhere('id', $this->data['carrera'])
            ->first();

        if ($carrera === null) {
            throw new RowImportFailedException("La carrera '{$this->data['carrera']}' no existe.");
        }

        $alumno->carrera_id = $carrera->id;

        $alumno->semestre_actual_id = Semestre::from($this->data['semestre_actual_id']);
        $alumno->fecha_ingreso = $this->data['fecha_ingreso'];

        return $alumno;
    }

    public function saveRecord(): void
    {
        parent::saveRecord();

        $grupoState = $this->data['grupo'] ?? null;

        if (blank($grupoState)) {
            throw new RowImportFailedException('El grupo no fue proporcionado.');
        }

        $grupo = Grupo::where('nombre', $grupoState)->first();

        if ($grupo === null) {
            throw new RowImportFailedException("El grupo '{$grupoState}' no existe.");
        }

        $periodo = PeriodoEscolar::where('activo', true)->first();

        if ($periodo === null) {
            throw new RowImportFailedException('No hay un periodo escolar activo.');
        }

        Inscripcion::updateOrCreate(
            [
                'alumno_id' => $this->record->id,
                'periodo_escolar_id' => $periodo->id,
            ],
            [
                'grupo_id' => $grupo->id,
                'fecha_inscripcion' => $this->data['fecha_ingreso'] ?? today(),
                'status' => 'Inscrito',
            ],
        );
    }

    public function getJobConnection(): ?string
    {
        return app()->isLocal() ? 'sync' : null;
    }

    public static function parseDate(?string $state): ?string
    {
        if (blank($state)) {
            return null;
        }

        if (preg_match('/^\d{2}\/\d{2}\/\d{4}$/', $state)) {
            return Carbon::createFromFormat('d/m/Y', $state)->format('Y-m-d');
        }

        return $state;
    }

    public static function getCompletedNotificationBody(Import $import): string
    {
        $body = 'La importación de alumnos ha finalizado. '.Number::format($import->successful_rows).' filas importadas.';

        if ($failedRowsCount = $import->getFailedRowsCount()) {
            $body .= ' '.Number::format($failedRowsCount).' filas fallaron.';
        }

        return $body;
    }
}

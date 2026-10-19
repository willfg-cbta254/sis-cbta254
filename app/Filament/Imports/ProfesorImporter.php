<?php

namespace App\Filament\Imports;

use App\Models\Profesor;
use App\Models\User;
use Carbon\Carbon;
use Filament\Actions\Imports\ImportColumn;
use Filament\Actions\Imports\Importer;
use Filament\Actions\Imports\Models\Import;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Number;

class ProfesorImporter extends Importer
{
    protected static ?string $model = Profesor::class;

    public static function getColumns(): array
    {
        return [
            ImportColumn::make('email')
                ->label('Correo electrónico')
                ->requiredMapping()
                ->rules(['required', 'email'])
                ->example('profesor@cbta254.edu.mx')
                ->fillRecordUsing(fn ($record, $state): null => null),
            ImportColumn::make('name')
                ->label('Nombre completo')
                ->requiredMapping()
                ->rules(['required', 'string', 'max:255'])
                ->example('María López Hernández')
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
                ->example('15/03/1985')
                ->fillRecordUsing(fn ($record, $state): null => null),
            ImportColumn::make('direccion')
                ->label('Dirección')
                ->rules(['nullable', 'string', 'max:255'])
                ->example('Calle Falsa 123, Puebla')
                ->fillRecordUsing(fn ($record, $state): null => null),
            ImportColumn::make('numero_empleado')
                ->requiredMapping()
                ->label('Número de empleado')
                ->rules(['required'])
                ->example('245001'),
            ImportColumn::make('fecha_ingreso')
                ->label('Fecha de ingreso')
                ->rules(['date'])
                ->castStateUsing(fn ($state): ?string => static::parseDate($state))
                ->example('16/08/2007'),
            ImportColumn::make('especialidad')
                ->label('Especialidad')
                ->example('Ingeniero'),
        ];
    }

    public function resolveRecord(): ?Profesor
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

        $user->assignRole('Profesor');

        $profesor = Profesor::firstOrNew(['user_id' => $user->id]);

        return $profesor;
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
        $body = 'La importación de profesores ha finalizado. '.Number::format($import->successful_rows).' filas importadas.';

        if ($failedRowsCount = $import->getFailedRowsCount()) {
            $body .= ' '.Number::format($failedRowsCount).' filas fallaron.';
        }

        return $body;
    }
}

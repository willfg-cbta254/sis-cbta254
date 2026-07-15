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
                ->fillRecordUsing(fn ($record, $state): null => null),
            ImportColumn::make('name')
                ->label('Nombre completo')
                ->requiredMapping()
                ->rules(['required', 'string', 'max:255'])
                ->fillRecordUsing(fn ($record, $state): null => null),
            ImportColumn::make('password')
                ->label('Contraseña')
                ->rules(['nullable', 'string', 'min:8'])
                ->fillRecordUsing(fn ($record, $state): null => null),
            ImportColumn::make('curp')
                ->label('CURP')
                ->rules(['nullable', 'string', 'max:18'])
                ->fillRecordUsing(fn ($record, $state): null => null),
            ImportColumn::make('telefono')
                ->label('Teléfono')
                ->rules(['nullable', 'string', 'max:20'])
                ->fillRecordUsing(fn ($record, $state): null => null),
            ImportColumn::make('fecha_nacimiento')
                ->label('Fecha de nacimiento')
                ->rules(['nullable', 'date'])
                ->castStateUsing(fn ($state): ?string => blank($state) ? null : (preg_match('/^\d{2}\/\d{2}\/\d{4}$/', $state) ? Carbon::createFromFormat('d/m/Y', $state)->format('Y-m-d') : $state))
                ->fillRecordUsing(fn ($record, $state): null => null),
            ImportColumn::make('direccion')
                ->label('Dirección')
                ->rules(['nullable', 'string', 'max:255'])
                ->fillRecordUsing(fn ($record, $state): null => null),
            ImportColumn::make('numero_empleado')
                ->requiredMapping()
                ->label('Número de empleado')
                ->rules(['required']),
            ImportColumn::make('fecha_ingreso')
                ->label('Fecha de ingreso')
                ->rules(['date'])
                ->castStateUsing(fn ($state): ?string => blank($state) ? null : (preg_match('/^\d{2}\/\d{2}\/\d{4}$/', $state) ? Carbon::createFromFormat('d/m/Y', $state)->format('Y-m-d') : $state)),
            ImportColumn::make('especialidad')
                ->label('Especialidad'),
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

        $profesor = Profesor::firstOrNew(['user_id' => $user->id]);

        return $profesor;
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

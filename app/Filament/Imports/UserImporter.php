<?php

namespace App\Filament\Imports;

use App\Models\User;
use Carbon\Carbon;
use Filament\Actions\Imports\ImportColumn;
use Filament\Actions\Imports\Importer;
use Filament\Actions\Imports\Models\Import;
use Illuminate\Support\Number;

class UserImporter extends Importer
{
    protected static ?string $model = User::class;

    public static function getColumns(): array
    {
        return [
            ImportColumn::make('name')
                ->requiredMapping()
                ->label('Nombre completo')
                ->rules(['required']),
            ImportColumn::make('email')
                ->requiredMapping()
                ->label('Correo electrónico')
                ->rules(['required', 'email']),
            ImportColumn::make('password')
                ->requiredMapping()
                ->label('Contraseña')
                ->rules(['required']),
            ImportColumn::make('curp')
                ->label('CURP'),
            ImportColumn::make('telefono')
                ->label('Teléfono'),
            ImportColumn::make('fecha_nacimiento')
                ->label('Fecha de nacimiento')
                ->rules(['date'])
                ->castStateUsing(fn ($state): ?string => blank($state) ? null : (preg_match('/^\d{2}\/\d{2}\/\d{4}$/', $state) ? Carbon::createFromFormat('d/m/Y', $state)->format('Y-m-d') : $state)),
            ImportColumn::make('direccion')
                ->label('Dirección'),
            ImportColumn::make('activo')
                ->requiredMapping()
                ->boolean()
                ->label('Activo')
                ->rules(['boolean']),
        ];
    }

    public function resolveRecord(): User
    {
        return new User;
    }

    protected function beforeSave(): void
    {
        if (filled($this->data['password'] ?? null)) {
            $this->record->password = bcrypt($this->data['password']);
        }
    }

    public function getJobConnection(): ?string
    {
        return app()->isLocal() ? 'sync' : null;
    }

    public static function getCompletedNotificationBody(Import $import): string
    {
        $body = 'La importación de usuarios ha finalizado. '.Number::format($import->successful_rows).' filas importadas.';

        if ($failedRowsCount = $import->getFailedRowsCount()) {
            $body .= ' '.Number::format($failedRowsCount).' filas fallaron.';
        }

        return $body;
    }
}

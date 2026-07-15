<?php

namespace App\Filament\Imports;

use App\Enums\Semestre;
use App\Models\Grupo;
use Filament\Actions\Imports\ImportColumn;
use Filament\Actions\Imports\Importer;
use Filament\Actions\Imports\Models\Import;
use Illuminate\Support\Number;

class GrupoImporter extends Importer
{
    protected static ?string $model = Grupo::class;

    public static function getColumns(): array
    {
        return [
            ImportColumn::make('nombre')
                ->requiredMapping()
                ->rules(['required']),
            ImportColumn::make('semestre_id')
                ->requiredMapping()
                ->label('Semestre')
                ->rules(['required', 'string', 'in:'.implode(',', array_column(Semestre::cases(), 'value'))])
                ->castStateUsing(fn ($state): ?string => $state ? ucfirst(strtolower($state)) : null),
            ImportColumn::make('carrera')
                ->requiredMapping()
                ->relationship(resolveUsing: ['nombre', 'id'])
                ->rules(['required']),
            ImportColumn::make('generacion')
                ->requiredMapping()
                ->relationship(resolveUsing: ['clave', 'id'])
                ->rules(['required']),
            ImportColumn::make('turno')
                ->requiredMapping()
                ->rules(['required']),
            ImportColumn::make('capacidad')
                ->requiredMapping()
                ->numeric()
                ->rules(['required', 'integer']),
            ImportColumn::make('activo')
                ->requiredMapping()
                ->boolean()
                ->rules(['required', 'boolean']),
        ];
    }

    public function resolveRecord(): Grupo
    {
        return new Grupo;
    }

    public static function getCompletedNotificationBody(Import $import): string
    {
        $body = 'La importación de grupos ha finalizado. '.Number::format($import->successful_rows).' filas importadas.';

        if ($failedRowsCount = $import->getFailedRowsCount()) {
            $body .= ' '.Number::format($failedRowsCount).' filas fallaron.';
        }

        return $body;
    }
}

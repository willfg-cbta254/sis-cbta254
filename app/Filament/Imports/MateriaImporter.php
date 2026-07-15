<?php

namespace App\Filament\Imports;

use App\Enums\Semestre;
use App\Enums\TipoMateria;
use App\Models\Materia;
use Filament\Actions\Imports\ImportColumn;
use Filament\Actions\Imports\Importer;
use Filament\Actions\Imports\Models\Import;
use Illuminate\Support\Number;

class MateriaImporter extends Importer
{
    protected static ?string $model = Materia::class;

    public static function getColumns(): array
    {
        return [
            ImportColumn::make('nombre')
                ->requiredMapping()
                ->rules(['required']),
            ImportColumn::make('clave')
                ->requiredMapping()
                ->rules(['required']),
            ImportColumn::make('tipo')
                ->label('Tipo')
                ->rules(['nullable', 'string', 'in:'.implode(',', array_column(TipoMateria::cases(), 'value'))]),
            ImportColumn::make('semestre_id')
                ->requiredMapping()
                ->label('Semestre')
                ->rules(['required', 'string', 'in:'.implode(',', array_column(Semestre::cases(), 'value'))])
                ->castStateUsing(fn ($state): ?string => $state ? ucfirst(strtolower($state)) : null),
            ImportColumn::make('horas_semana')
                ->requiredMapping()
                ->numeric()
                ->rules(['required', 'integer']),
        ];
    }

    public function resolveRecord(): Materia
    {
        return new Materia;
    }

    public static function getCompletedNotificationBody(Import $import): string
    {
        $body = 'La importación de materias ha finalizado. '.Number::format($import->successful_rows).' filas importadas.';

        if ($failedRowsCount = $import->getFailedRowsCount()) {
            $body .= ' '.Number::format($failedRowsCount).' filas fallaron.';
        }

        return $body;
    }
}

<?php

namespace App\Filament\Imports;

use App\Models\PeriodoEscolar;
use Carbon\Carbon;
use Filament\Actions\Imports\ImportColumn;
use Filament\Actions\Imports\Importer;
use Filament\Actions\Imports\Models\Import;
use Illuminate\Support\Number;

class PeriodoEscolarImporter extends Importer
{
    protected static ?string $model = PeriodoEscolar::class;

    public static function getColumns(): array
    {
        return [
            ImportColumn::make('clave')
                ->requiredMapping()
                ->rules(['required']),
            ImportColumn::make('fecha_inicio')
                ->requiredMapping()
                ->rules(['required', 'date'])
                ->castStateUsing(fn ($state): ?string => blank($state) ? null : (preg_match('/^\d{2}\/\d{2}\/\d{4}$/', $state) ? Carbon::createFromFormat('d/m/Y', $state)->format('Y-m-d') : $state)),
            ImportColumn::make('fecha_fin')
                ->requiredMapping()
                ->rules(['required', 'date'])
                ->castStateUsing(fn ($state): ?string => blank($state) ? null : (preg_match('/^\d{2}\/\d{2}\/\d{4}$/', $state) ? Carbon::createFromFormat('d/m/Y', $state)->format('Y-m-d') : $state)),
            ImportColumn::make('activo')
                ->requiredMapping()
                ->boolean()
                ->rules(['required', 'boolean']),
        ];
    }

    public function resolveRecord(): PeriodoEscolar
    {
        return new PeriodoEscolar;
    }

    public function getJobConnection(): ?string
    {
        return app()->isLocal() ? 'sync' : null;
    }

    public static function getCompletedNotificationBody(Import $import): string
    {
        $body = 'La importación de periodos escolares ha finalizado. '.Number::format($import->successful_rows).' filas importadas.';

        if ($failedRowsCount = $import->getFailedRowsCount()) {
            $body .= ' '.Number::format($failedRowsCount).' filas fallaron.';
        }

        return $body;
    }
}

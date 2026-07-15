<?php

namespace App\Filament\Imports;

use App\Models\Generacion;
use Filament\Actions\Imports\ImportColumn;
use Filament\Actions\Imports\Importer;
use Filament\Actions\Imports\Models\Import;
use Illuminate\Support\Number;

class GeneracionImporter extends Importer
{
    protected static ?string $model = Generacion::class;

    public static function getColumns(): array
    {
        return [
            ImportColumn::make('clave')
                ->requiredMapping()
                ->rules(['required']),
            ImportColumn::make('anio_inicio')
                ->requiredMapping()
                ->numeric()
                ->rules(['required', 'integer']),
            ImportColumn::make('anio_fin')
                ->requiredMapping()
                ->numeric()
                ->rules(['required', 'integer']),
        ];
    }

    public function resolveRecord(): Generacion
    {
        return new Generacion;
    }

    public static function getCompletedNotificationBody(Import $import): string
    {
        $body = 'La importación de generaciones ha finalizado. '.Number::format($import->successful_rows).' filas importadas.';

        if ($failedRowsCount = $import->getFailedRowsCount()) {
            $body .= ' '.Number::format($failedRowsCount).' filas fallaron.';
        }

        return $body;
    }
}

<?php

namespace App\Filament\Imports;

use App\Models\Inscripcion;
use Carbon\Carbon;
use Filament\Actions\Imports\ImportColumn;
use Filament\Actions\Imports\Importer;
use Filament\Actions\Imports\Models\Import;
use Illuminate\Support\Number;

class InscripcionImporter extends Importer
{
    protected static ?string $model = Inscripcion::class;

    public static function getColumns(): array
    {
        return [
            ImportColumn::make('alumno')
                ->requiredMapping()
                ->relationship(resolveUsing: 'matricula')
                ->rules(['required']),
            ImportColumn::make('grupo')
                ->requiredMapping()
                ->relationship(resolveUsing: 'nombre')
                ->rules(['required']),
            ImportColumn::make('periodoEscolar')
                ->requiredMapping()
                ->relationship(resolveUsing: 'clave')
                ->rules(['required']),
            ImportColumn::make('fecha_inscripcion')
                ->requiredMapping()
                ->rules(['required', 'date'])
                ->castStateUsing(fn ($state): ?string => blank($state) ? null : (preg_match('/^\d{2}\/\d{2}\/\d{4}$/', $state) ? Carbon::createFromFormat('d/m/Y', $state)->format('Y-m-d') : $state)),
            ImportColumn::make('status')
                ->requiredMapping()
                ->rules(['required']),
        ];
    }

    public function resolveRecord(): Inscripcion
    {
        return new Inscripcion;
    }

    public static function getCompletedNotificationBody(Import $import): string
    {
        $body = 'La importación de inscripciones ha finalizado. '.Number::format($import->successful_rows).' filas importadas.';

        if ($failedRowsCount = $import->getFailedRowsCount()) {
            $body .= ' '.Number::format($failedRowsCount).' filas fallaron.';
        }

        return $body;
    }
}

<?php

namespace App\Filament\Imports;

use App\Enums\Semestre;
use App\Models\Generacion;
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
                ->rules(['required'])
                ->example('1 "A"'),
            ImportColumn::make('semestre_id')
                ->requiredMapping()
                ->label('Semestre')
                ->rules(['required', 'string', 'in:'.implode(',', array_column(Semestre::cases(), 'value'))])
                ->castStateUsing(fn ($state): ?string => Semestre::normalize($state))
                ->example('Primero'),
            ImportColumn::make('carrera')
                ->requiredMapping()
                ->relationship(resolveUsing: ['nombre', 'id'])
                ->rules(['required'])
                ->example('Bachillerato Tecnológico'),
            ImportColumn::make('generacion')
                ->requiredMapping()
                ->relationship(resolveUsing: fn (?string $state): ?Generacion => self::resolveOrCreateGeneracion($state))
                ->rules(['required'])
                ->example('2026-2029'),
            ImportColumn::make('turno')
                ->requiredMapping()
                ->rules(['required', 'string', 'in:Matutino,Vespertino'])
                ->castStateUsing(function (?string $state): ?string {
                    if (blank($state)) {
                        return null;
                    }

                    $state = trim($state);

                    return match ($state) {
                        '1' => 'Matutino',
                        '2' => 'Vespertino',
                        default => ucfirst(strtolower($state)),
                    };
                })
                ->example('Matutino'),
            ImportColumn::make('capacidad')
                ->requiredMapping()
                ->numeric()
                ->rules(['required', 'integer'])
                ->example(45),
            ImportColumn::make('activo')
                ->requiredMapping()
                ->boolean()
                ->rules(['required', 'boolean'])
                ->example(true),
        ];
    }

    protected static function resolveOrCreateGeneracion(?string $state): ?Generacion
    {
        if (blank($state)) {
            return null;
        }

        $clave = trim($state);

        $generacion = Generacion::query()
            ->where('clave', $clave)
            ->when(is_numeric($clave), fn ($query) => $query->orWhere('id', (int) $clave))
            ->first();

        if ($generacion) {
            return $generacion;
        }

        if (! preg_match_all('/\d{4}/', $clave, $matches)) {
            return null;
        }

        $anios = array_map(intval(...), $matches[0]);

        if (count($anios) < 2) {
            return null;
        }

        return Generacion::firstOrCreate(
            ['clave' => $clave],
            [
                'anio_inicio' => $anios[0],
                'anio_fin' => end($anios),
            ],
        );
    }

    public function resolveRecord(): Grupo
    {
        return new Grupo;
    }

    public function getJobConnection(): ?string
    {
        return app()->isLocal() ? 'sync' : null;
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

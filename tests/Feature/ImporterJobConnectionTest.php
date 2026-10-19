<?php

use App\Filament\Imports\CargaAcademicaImporter;
use App\Filament\Imports\GeneracionImporter;
use App\Filament\Imports\InscripcionImporter;
use App\Filament\Imports\PeriodoEscolarImporter;
use App\Filament\Imports\UserImporter;
use Filament\Actions\Imports\Models\Import;

it('runs imports synchronously in the local environment', function (string $importer) {
    app()->detectEnvironment(fn (): string => 'local');

    expect((new $importer(new Import, [], []))->getJobConnection())->toBe('sync');
})->with([
    'carga academica' => CargaAcademicaImporter::class,
    'generacion' => GeneracionImporter::class,
    'inscripcion' => InscripcionImporter::class,
    'periodo escolar' => PeriodoEscolarImporter::class,
    'usuario' => UserImporter::class,
]);

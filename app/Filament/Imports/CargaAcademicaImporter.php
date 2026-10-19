<?php

namespace App\Filament\Imports;

use App\Models\CargaAcademica;
use App\Models\Grupo;
use App\Models\Materia;
use App\Models\PeriodoEscolar;
use App\Models\Profesor;
use Filament\Actions\Imports\Exceptions\RowImportFailedException;
use Filament\Actions\Imports\ImportColumn;
use Filament\Actions\Imports\Importer;
use Filament\Actions\Imports\Models\Import;
use Illuminate\Support\Number;

class CargaAcademicaImporter extends Importer
{
    protected static ?string $model = CargaAcademica::class;

    public static function getColumns(): array
    {
        return [
            ImportColumn::make('profesor')
                ->label('Profesor')
                ->helperText('Número de empleado, ID o email del profesor')
                ->example('EMP-001')
                ->exampleHeader('profesor')
                ->requiredMapping()
                ->rules(['required', 'string'])
                ->fillRecordUsing(fn (): null => null),

            ImportColumn::make('materia')
                ->label('Materia')
                ->helperText('Clave de la materia (ej. MAT-III) o ID')
                ->example('MAT-301')
                ->exampleHeader('materia')
                ->requiredMapping()
                ->relationship(resolveUsing: ['clave', 'id'])
                ->rules(['required'])
                ->fillRecordUsing(fn (): null => null),

            ImportColumn::make('grupo')
                ->label('Grupo')
                ->helperText('Nombre del grupo (ej. 5A OFI) o ID')
                ->example('5A OFI')
                ->exampleHeader('grupo')
                ->requiredMapping()
                ->relationship(resolveUsing: ['nombre', 'id'])
                ->rules(['required'])
                ->fillRecordUsing(fn (): null => null),

            ImportColumn::make('periodo_escolar')
                ->label('Periodo escolar')
                ->helperText('Clave del periodo (ej. 2025-A) o ID')
                ->example('2025-A')
                ->exampleHeader('periodo_escolar')
                ->requiredMapping()
                ->relationship(name: 'periodoEscolar', resolveUsing: ['clave', 'id'])
                ->rules(['required'])
                ->fillRecordUsing(fn (): null => null),
        ];
    }

    public function resolveRecord(): ?CargaAcademica
    {
        $profesorState = $this->data['profesor'] ?? null;
        $materiaState = $this->data['materia'] ?? null;
        $grupoState = $this->data['grupo'] ?? null;
        $periodoState = $this->data['periodo_escolar'] ?? null;

        if (blank($profesorState) || blank($materiaState) || blank($grupoState) || blank($periodoState)) {
            return null;
        }

        // Profesor: numero_empleado | id | email (via users)
        $profesor = Profesor::where('numero_empleado', $profesorState)
            ->orWhere('id', $profesorState)
            ->first();

        if ($profesor === null) {
            $profesor = Profesor::whereHas('user', fn ($q) => $q->where('email', $profesorState))->first();
        }

        if ($profesor === null) {
            throw new RowImportFailedException("El profesor '{$profesorState}' no existe (buscado por número de empleado, ID o email).");
        }

        // Materia: clave | id (relationship) + también nombre como fallback
        $materia = Materia::where('clave', $materiaState)
            ->orWhere('id', $materiaState)
            ->first();

        if ($materia === null) {
            $materia = Materia::where('nombre', $materiaState)->first();
        }

        if ($materia === null) {
            throw new RowImportFailedException("La materia '{$materiaState}' no existe (clave/ID/nombre).");
        }

        // Grupo: nombre | id — notar que nombre es único compuesto, se toma exacto
        $grupo = Grupo::where('nombre', $grupoState)
            ->orWhere('id', $grupoState)
            ->first();

        if ($grupo === null) {
            throw new RowImportFailedException("El grupo '{$grupoState}' no existe.");
        }

        // Periodo: clave | id
        $periodo = PeriodoEscolar::where('clave', $periodoState)
            ->orWhere('id', $periodoState)
            ->first();

        if ($periodo === null) {
            throw new RowImportFailedException("El periodo escolar '{$periodoState}' no existe (clave o ID).");
        }

        // Respetar unicidad compuesta: profesor+materia+grupo+periodo
        $existing = CargaAcademica::where('profesor_id', $profesor->id)
            ->where('materia_id', $materia->id)
            ->where('grupo_id', $grupo->id)
            ->where('periodo_escolar_id', $periodo->id)
            ->first();

        if ($existing) {
            return $existing;
        }

        return new CargaAcademica([
            'profesor_id' => $profesor->id,
            'materia_id' => $materia->id,
            'grupo_id' => $grupo->id,
            'periodo_escolar_id' => $periodo->id,
        ]);
    }

    public function getJobConnection(): ?string
    {
        return app()->isLocal() ? 'sync' : null;
    }

    public static function getCompletedNotificationBody(Import $import): string
    {
        $body = 'La importación de cargas académicas ha finalizado. '.Number::format($import->successful_rows).' filas importadas.';

        if ($failedRowsCount = $import->getFailedRowsCount()) {
            $body .= ' '.Number::format($failedRowsCount).' filas fallaron.';
        }

        return $body;
    }
}

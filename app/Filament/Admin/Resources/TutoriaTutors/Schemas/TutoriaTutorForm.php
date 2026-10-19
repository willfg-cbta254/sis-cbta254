<?php

namespace App\Filament\Admin\Resources\TutoriaTutors\Schemas;

use App\Models\Grupo;
use App\Models\Profesor;
use App\Models\TutoriaTutor;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;

class TutoriaTutorForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('profesor_id')
                    ->label('Docente')
                    ->options(Profesor::with('user')->get()->pluck('user.name', 'id'))
                    ->searchable()
                    ->unique(ignoreRecord: true)
                    ->required(),
                Select::make('grupo_id')
                    ->label('Grupo asignado como Tutor Académico')
                    ->options(fn ($record): array => self::grupoOptions($record))
                    ->searchable()
                    ->helperText('Un solo docente puede ser tutor de un solo grupo. Solo se muestran grupos sin tutor asignado.'),
                Toggle::make('activo')
                    ->default(true),
                Textarea::make('observaciones')
                    ->rows(3)
                    ->maxLength(500),
            ]);
    }

    /**
     * @return array<int, string>
     */
    public static function grupoOptions(?TutoriaTutor $record = null): array
    {
        return Grupo::with('carrera')
            ->when(
                $record,
                fn ($query) => $query->where(function ($query) use ($record) {
                    $query->whereNull('tutor_id')
                        ->orWhere('tutor_id', $record->profesor_id);
                }),
                fn ($query) => $query->whereNull('tutor_id'),
            )
            ->get()
            ->mapWithKeys(fn (Grupo $grupo): array => [
                $grupo->id => $grupo->nombre.' - '.$grupo->carrera?->nombre,
            ])
            ->all();
    }
}

<?php

namespace App\Filament\Admin\Resources\TutoriaIncidencias\Schemas;

use App\Models\Grupo;
use App\Models\PeriodoEscolar;
use App\Models\Profesor;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\ToggleButtons;
use Filament\Schemas\Schema;
use Illuminate\Support\Facades\Auth;

class TutoriaIncidenciaForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('grupo_id')
                    ->label('Grupo')
                    ->options(Grupo::with(['tutor.user'])->get()->mapWithKeys(fn ($g) => [
                        $g->id => $g->nombre.($g->tutor ? ' - Tutor: '.$g->tutor->user->name : ''),
                    ]))
                    ->searchable()
                    ->reactive()
                    ->afterStateUpdated(function ($state, callable $set) {
                        if ($state) {
                            $grupo = Grupo::with('tutor')->find($state);
                            if ($grupo && $grupo->tutor_id) {
                                $set('tutor_id', $grupo->tutor_id);
                            }
                        }
                    })
                    ->required(),
                Select::make('tutor_id')
                    ->label('Tutor Académico')
                    ->options(Profesor::with('user')->get()->pluck('user.name', 'id'))
                    ->searchable()
                    ->required(),
                Select::make('periodo_escolar_id')
                    ->label('Periodo Escolar')
                    ->options(PeriodoEscolar::pluck('clave', 'id'))
                    ->default(fn () => PeriodoEscolar::where('activo', true)->first()?->id)
                    ->required(),
                ToggleButtons::make('tipo')
                    ->options([
                        'grupal' => 'Grupal',
                        'individual' => 'Individual',
                    ])
                    ->colors([
                        'grupal' => 'info',
                        'individual' => 'warning',
                    ])
                    ->icons([
                        'grupal' => 'heroicon-o-user-group',
                        'individual' => 'heroicon-o-user',
                    ])
                    ->inline()
                    ->required()
                    ->reactive(),
                Select::make('alumnos')
                    ->label('Alumnos involucrados')
                    ->multiple()
                    ->relationship('alumnos', 'matricula')
                    ->preload()
                    ->searchable()
                    ->visible(fn ($get) => $get('tipo') === 'individual')
                    ->required(fn ($get) => $get('tipo') === 'individual'),
                Textarea::make('descripcion')
                    ->label('Descripción de la incidencia')
                    ->required()
                    ->rows(4),
                Select::make('registrado_por_id')
                    ->label('Registrado por')
                    ->options(Profesor::with('user')->get()->pluck('user.name', 'id'))
                    ->default(function () {
                        $user = Auth::user();
                        if ($user) {
                            $profesor = Profesor::where('user_id', $user->id)->first();

                            return $profesor?->id;
                        }

                        return null;
                    })
                    ->searchable(),
            ]);
    }
}

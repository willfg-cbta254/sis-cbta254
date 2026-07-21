<?php

namespace App\Filament\Alumno\Pages;

use App\Models\Calificacion;
use BackedEnum;
use Filament\Actions\Action;
use Filament\Pages\Page;
use Filament\Tables\Columns\BooleanColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class MisCalificaciones extends Page implements HasTable
{
    use InteractsWithTable;

    public static function can(): bool
    {
        return auth()->user()?->can('ver calificaciones') ?? false;
    }

    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-star';

    protected static ?string $title = 'Mis Calificaciones';

    protected string $view = 'filament.pages.mis-calificaciones';

    protected ?string $pollingInterval = '5s';

    protected function getHeaderActions(): array
    {
        return [
            Action::make('imprimir_boleta')
                ->label('Imprimir Boleta')
                ->icon('heroicon-o-printer')
                ->color('success')
                ->url(fn () => route('boletas.download', auth()->user()?->alumno))
                ->openUrlInNewTab(),
        ];
    }

    public function table(Table $table): Table
    {
        return $table
            ->query(Calificacion::where('alumno_id', auth()->user()?->alumno?->id))
            ->columns([
                TextColumn::make('materia.nombre'),
                TextColumn::make('parcial1')->numeric(2)->label('Parcial 1'),
                TextColumn::make('parcial2')->numeric(2)->label('Parcial 2'),
                TextColumn::make('parcial3')->numeric(2)->label('Parcial 3'),
                TextColumn::make('promedio')->numeric(2)
                    ->color(fn (?float $state): string => match (true) {
                        $state === null => 'gray',
                        $state >= 6 => 'success',
                        default => 'danger',
                    }),
                TextColumn::make('extraordinario')->numeric(2)->placeholder('-'),
                TextColumn::make('curso_intersemestral')->numeric(2)->placeholder('-'),
                BooleanColumn::make('recursamiento'),
                TextColumn::make('profesor.user.name')->label('Profesor'),
            ])
            ->filters([
                SelectFilter::make('materia_id')->relationship('materia', 'nombre'),
            ]);
    }
}

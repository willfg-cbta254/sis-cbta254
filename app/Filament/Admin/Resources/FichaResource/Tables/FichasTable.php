<?php

namespace App\Filament\Admin\Resources\FichaResource\Tables;

use App\Models\Ficha;
use Filament\Actions\Action;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class FichasTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('folio')
                    ->label('Folio')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('nombre_completo')
                    ->label('Nombre')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('ciclo_escolar')
                    ->label('Ciclo Escolar')
                    ->badge()
                    ->sortable(),
                TextColumn::make('genero')
                    ->label('Género')
                    ->badge()
                    ->formatStateUsing(fn (string $state): string => $state === 'femenino' ? 'Femenino' : 'Masculino'),
                TextColumn::make('tipo_secundaria')
                    ->label('Tipo Sec.')
                    ->badge()
                    ->formatStateUsing(fn (string $state): string => $state === 'tecnica' ? 'Técnica' : 'Telesecundaria'),
                TextColumn::make('promedio')
                    ->label('Promedio')
                    ->sortable(),
                TextColumn::make('tutor_nombre_completo')
                    ->label('Tutor')
                    ->toggleable(),
                TextColumn::make('created_at')
                    ->label('Registrado')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(),
            ])
            ->recordActions([
                ViewAction::make(),
                EditAction::make(),
                Action::make('download')
                    ->label('Descargar PDF')
                    ->icon('heroicon-o-arrow-down-tray')
                    ->color('success')
                    ->url(fn (Ficha $record): string => route('fichas.download', $record))
                    ->openUrlInNewTab(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ])
            ->defaultSort('created_at', 'desc');
    }
}

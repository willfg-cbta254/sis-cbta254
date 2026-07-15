<?php

namespace App\Filament\Admin\Resources\Galleries\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class GalleriesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                ImageColumn::make('image')
                    ->label('Imagen')
                    ->circular()
                    ->size(50),
                TextColumn::make('title')
                    ->label('Título')
                    ->searchable()
                    ->sortable()
                    ->limit(30),
                TextColumn::make('category')
                    ->label('Categoría')
                    ->sortable()
                    ->formatStateUsing(fn ($state) => match ($state) {
                        'instalaciones' => 'Instalaciones',
                        'eventos' => 'Eventos',
                        'actividades' => 'Actividades',
                        default => $state ? ucfirst($state) : '-',
                    }),
                IconColumn::make('is_active')
                    ->boolean()
                    ->sortable()
                    ->label('Activo'),
                TextColumn::make('sort_order')
                    ->label('Orden')
                    ->sortable(),
            ])
            ->filters([])
            ->recordActions([
                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ])
            ->defaultSort('sort_order');
    }
}

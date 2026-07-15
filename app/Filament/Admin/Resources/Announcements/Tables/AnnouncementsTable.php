<?php

namespace App\Filament\Admin\Resources\Announcements\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class AnnouncementsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('title')
                    ->label('Título')
                    ->searchable()
                    ->sortable()
                    ->limit(50),
                TextColumn::make('type')
                    ->label('Tipo')
                    ->sortable()
                    ->formatStateUsing(fn ($state) => match ($state) {
                        'community' => 'Comunidad',
                        'group' => 'Grupos',
                        'student' => 'Alumnos',
                        default => $state,
                    })
                    ->badge()
                    ->color(fn ($state) => match ($state) {
                        'community' => 'success',
                        'group' => 'warning',
                        'student' => 'info',
                        default => 'gray',
                    }),
                IconColumn::make('is_published')
                    ->boolean()
                    ->sortable()
                    ->label('Publicado'),
                TextColumn::make('published_at')
                    ->dateTime()
                    ->sortable()
                    ->label('Publicado el'),
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
            ->defaultSort('created_at', 'desc');
    }
}

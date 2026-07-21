<?php

namespace App\Filament\ServiciosEscolares\Resources;

use App\Filament\ServiciosEscolares\Resources\PagoResource\Pages;
use App\Models\Pago;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class PagoResource extends Resource
{
    protected static ?string $model = Pago::class;

    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-banknotes';

    protected static ?string $modelLabel = 'Pago';

    protected static ?string $pluralModelLabel = 'Pagos';

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('alumno.matricula')
                    ->searchable()
                    ->sortable()
                    ->label('Matrícula'),
                TextColumn::make('alumno.user.name')
                    ->searchable()
                    ->label('Alumno'),
                TextColumn::make('conceptoPago.nombre')
                    ->badge()
                    ->label('Concepto'),
                TextColumn::make('periodoEscolar.clave')
                    ->badge()
                    ->label('Periodo')
                    ->toggleable(),
                TextColumn::make('monto')
                    ->numeric(2)
                    ->sortable()
                    ->prefix('$')
                    ->weight('bold'),
                TextColumn::make('referencia')
                    ->searchable()
                    ->label('Referencia'),
                TextColumn::make('fecha_pago')
                    ->date()
                    ->sortable()
                    ->label('Fecha'),
                TextColumn::make('metodo_pago')
                    ->badge()
                    ->formatStateUsing(fn (string $state): string => match ($state) {
                        'efectivo' => 'Efectivo',
                        'transferencia' => 'Transferencia',
                        'tarjeta' => 'Tarjeta',
                        'deposito' => 'Depósito',
                        default => $state,
                    })
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('estatus')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'pagado' => 'success',
                        'pendiente' => 'warning',
                        'cancelado' => 'danger',
                    })
                    ->formatStateUsing(fn (string $state): string => match ($state) {
                        'pagado' => 'Pagado',
                        'pendiente' => 'Pendiente',
                        'cancelado' => 'Cancelado',
                        default => $state,
                    }),
            ])
            ->filters([
                SelectFilter::make('periodo_escolar_id')
                    ->relationship('periodoEscolar', 'clave')
                    ->label('Periodo'),
                SelectFilter::make('concepto_pago_id')
                    ->relationship('conceptoPago', 'nombre')
                    ->label('Concepto'),
                SelectFilter::make('estatus')
                    ->options([
                        'pagado' => 'Pagado',
                        'pendiente' => 'Pendiente',
                        'cancelado' => 'Cancelado',
                    ]),
            ])
            ->defaultSort('fecha_pago', 'desc');
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListPagos::route('/'),
        ];
    }
}

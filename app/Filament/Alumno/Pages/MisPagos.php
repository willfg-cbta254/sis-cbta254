<?php

namespace App\Filament\Alumno\Pages;

use App\Models\Pago;
use BackedEnum;
use Filament\Pages\Page;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class MisPagos extends Page implements HasTable
{
    use InteractsWithTable;

    public static function can(): bool
    {
        return auth()->user()?->can('ver pagos') ?? false;
    }

    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-banknotes';

    protected static ?string $title = 'Mis Pagos';

    protected string $view = 'filament.pages.mis-pagos';

    public function table(Table $table): Table
    {
        return $table
            ->query(Pago::where('alumno_id', auth()->user()?->alumno?->id))
            ->columns([
                TextColumn::make('referencia')
                    ->searchable()
                    ->label('Referencia'),
                TextColumn::make('conceptoPago.nombre')
                    ->label('Concepto'),
                TextColumn::make('monto')
                    ->numeric(2)
                    ->prefix('$')
                    ->weight('bold'),
                TextColumn::make('metodo_pago')
                    ->badge()
                    ->formatStateUsing(fn (string $state): string => match ($state) {
                        'efectivo' => 'Efectivo',
                        'transferencia' => 'Transferencia',
                        'tarjeta' => 'Tarjeta',
                        'deposito' => 'Depósito',
                        default => $state,
                    }),
                TextColumn::make('fecha_pago')
                    ->date()
                    ->sortable()
                    ->label('Fecha'),
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
                SelectFilter::make('estatus')
                    ->options([
                        'pagado' => 'Pagado',
                        'pendiente' => 'Pendiente',
                        'cancelado' => 'Cancelado',
                    ]),
                SelectFilter::make('metodo_pago')
                    ->options([
                        'efectivo' => 'Efectivo',
                        'transferencia' => 'Transferencia',
                        'tarjeta' => 'Tarjeta',
                        'deposito' => 'Depósito',
                    ])
                    ->label('Método de pago'),
            ])
            ->defaultSort('fecha_pago', 'desc');
    }
}

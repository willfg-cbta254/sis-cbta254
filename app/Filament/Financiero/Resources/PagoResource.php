<?php

namespace App\Filament\Financiero\Resources;

use App\Filament\Financiero\Resources\PagoResource\Pages;
use App\Models\ConceptoPago;
use App\Models\Pago;
use App\Models\PeriodoEscolar;
use BackedEnum;
use Filament\Actions\Action;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Resource;
use Filament\Schemas\Components\Utilities\Set;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class PagoResource extends Resource
{
    protected static ?string $model = Pago::class;

    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-banknotes';

    protected static ?string $modelLabel = 'Pago';

    protected static ?string $pluralModelLabel = 'Pagos';

    public static function form(Schema $schema): Schema
    {
        return $schema->schema([
            Select::make('alumno_id')
                ->relationship('alumno', 'matricula')
                ->getOptionLabelFromRecordUsing(fn ($record) => "{$record->matricula} - {$record->user?->name}")
                ->searchable(['matricula'])->preload()->required()->label('Alumno'),
            Select::make('concepto_pago_id')
                ->relationship('conceptoPago', 'nombre', modifyQueryUsing: fn (Builder $query) => $query->where('activo', true))
                ->searchable()->preload()->required()->label('Concepto')
                ->live()
                ->afterStateUpdated(function (Set $set, $state): void {
                    if ($state) {
                        $concepto = ConceptoPago::find($state);
                        if ($concepto) {
                            $set('monto', $concepto->monto);
                        }
                    } else {
                        $set('monto', null);
                    }
                }),
            Select::make('periodo_escolar_id')
                ->relationship('periodoEscolar', 'clave')
                ->searchable()
                ->preload()
                ->required()
                ->label('Periodo Escolar')
                ->default(fn () => PeriodoEscolar::where('activo', true)->first()?->id),
            TextInput::make('monto')
                ->required()
                ->numeric()
                ->minValue(0)
                ->step(0.01)
                ->prefix('$'),
            TextInput::make('referencia')
                ->maxLength(100)
                ->label('Referencia')
                ->disabled()
                ->dehydrated()
                ->hiddenOn('create')
                ->hintIcon('heroicon-o-information-circle')
                ->hint('Se genera automáticamente'),
            DatePicker::make('fecha_pago')
                ->required()
                ->default(now())
                ->label('Fecha de pago'),
            Select::make('metodo_pago')
                ->options([
                    'efectivo' => 'Efectivo',
                    'transferencia' => 'Transferencia',
                    'tarjeta' => 'Tarjeta',
                    'deposito' => 'Depósito',
                ])
                ->required()
                ->label('Método de pago'),
            Select::make('estatus')
                ->options([
                    'pagado' => 'Pagado',
                    'pendiente' => 'Pendiente',
                    'cancelado' => 'Cancelado',
                ])
                ->required()
                ->default('pagado'),
            Textarea::make('observaciones')
                ->maxLength(65535)
                ->columnSpanFull(),
        ]);
    }

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
                SelectFilter::make('metodo_pago')
                    ->options([
                        'efectivo' => 'Efectivo',
                        'transferencia' => 'Transferencia',
                        'tarjeta' => 'Tarjeta',
                        'deposito' => 'Depósito',
                    ])
                    ->label('Método de pago'),
            ])
            ->defaultSort('fecha_pago', 'desc')
            ->actions([
                Action::make('imprimir_recibo')
                    ->label('Imprimir Recibo')
                    ->icon('heroicon-o-printer')
                    ->color('success')
                    ->url(fn ($record) => route('pagos.recibo', $record))
                    ->openUrlInNewTab(),
                EditAction::make(),
            ])
            ->bulkActions([BulkActionGroup::make([DeleteBulkAction::make()])]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListPagos::route('/'),
            'create' => Pages\CreatePago::route('/create'),
            'edit' => Pages\EditPago::route('/{record}/edit'),
        ];
    }

    public static function can(\UnitEnum|string $action, ?Model $record = null): bool
    {
        $permission = match ($action) {
            'viewAny', 'view' => 'ver pagos',
            'create' => 'crear pagos',
            'update' => 'editar pagos',
            'delete' => 'eliminar pagos',
            default => null,
        };

        return $permission ? auth()->user()?->can($permission) : false;
    }
}

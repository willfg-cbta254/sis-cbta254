<?php

namespace App\Filament\Admin\Widgets;

use App\Models\ConceptoPago;
use App\Models\Pago;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class PagosCuotaVoluntaria extends BaseWidget
{
    protected ?string $pollingInterval = '5s';

    protected function getStats(): array
    {
        $concepto = ConceptoPago::where('nombre', 'like', '%Cuota%Voluntaria%')->first();

        if (! $concepto) {
            return [
                Stat::make('Cuota Voluntaria', 'Sin concepto')
                    ->description('No se encontró el concepto')
                    ->icon('heroicon-o-banknotes')
                    ->color('gray'),
            ];
        }

        $pagados = Pago::where('concepto_pago_id', $concepto->id)
            ->where('estatus', 'pagado')
            ->count();

        $pendientes = Pago::where('concepto_pago_id', $concepto->id)
            ->where('estatus', 'pendiente')
            ->count();

        $cancelados = Pago::where('concepto_pago_id', $concepto->id)
            ->where('estatus', 'cancelado')
            ->count();

        return [
            Stat::make('Registrados', $pagados)
                ->description('Cuota de aportación voluntaria')
                ->icon('heroicon-o-check-circle')
                ->color('success'),
            Stat::make('Pendientes', $pendientes)
                ->description('Cuota de aportación voluntaria')
                ->icon('heroicon-o-clock')
                ->color('warning'),
            Stat::make('Cancelados', $cancelados)
                ->description('Cuota de aportación voluntaria')
                ->icon('heroicon-o-x-circle')
                ->color('danger'),
        ];
    }
}

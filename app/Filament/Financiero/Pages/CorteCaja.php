<?php

namespace App\Filament\Financiero\Pages;

use App\Models\ConceptoPago;
use App\Models\PeriodoEscolar;
use BackedEnum;
use Filament\Pages\Page;
use Illuminate\Support\Collection;

class CorteCaja extends Page
{
    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-calculator';

    protected static ?string $title = 'Corte de Caja';

    protected static ?string $navigationLabel = 'Corte de Caja';

    protected string $view = 'filament.admin.pages.corte-caja';

    public ?int $historial_periodo_id = null;

    protected ?string $pollingInterval = '5s';

    public function getPeriodoActivo(): ?PeriodoEscolar
    {
        return PeriodoEscolar::where('activo', true)->first();
    }

    public function getPeriodos(): Collection
    {
        return PeriodoEscolar::orderBy('clave', 'desc')->get();
    }

    public function getReporteActivo(): Collection
    {
        $periodo = $this->getPeriodoActivo();

        if (! $periodo) {
            return collect();
        }

        return $this->calcularReporte($periodo->id);
    }

    public function getTotalesActivos(): array
    {
        return $this->calcularTotales($this->getReporteActivo());
    }

    public function getReporteHistorial(): Collection
    {
        if (! $this->historial_periodo_id) {
            return collect();
        }

        return $this->calcularReporte($this->historial_periodo_id);
    }

    public function getTotalesHistorial(): array
    {
        return $this->calcularTotales($this->getReporteHistorial());
    }

    private function calcularReporte(int $periodoId): Collection
    {
        return ConceptoPago::withCount([
            'pagos as pagados_count' => fn ($q) => $q->where('estatus', 'pagado')
                ->where('periodo_escolar_id', $periodoId),
            'pagos as pendientes_count' => fn ($q) => $q->where('estatus', 'pendiente')
                ->where('periodo_escolar_id', $periodoId),
            'pagos as cancelados_count' => fn ($q) => $q->where('estatus', 'cancelado')
                ->where('periodo_escolar_id', $periodoId),
        ])
            ->with(['pagos' => fn ($q) => $q->where('periodo_escolar_id', $periodoId)])
            ->get()
            ->map(fn (ConceptoPago $concepto) => [
                'nombre' => $concepto->nombre,
                'pagados_count' => $concepto->pagados_count,
                'pagados_total' => $concepto->pagos->where('estatus', 'pagado')->sum('monto'),
                'pendientes_count' => $concepto->pendientes_count,
                'pendientes_total' => $concepto->pagos->where('estatus', 'pendiente')->sum('monto'),
                'cancelados_count' => $concepto->cancelados_count,
                'cancelados_total' => $concepto->pagos->where('estatus', 'cancelado')->sum('monto'),
            ]);
    }

    private function calcularTotales(Collection $reporte): array
    {
        return [
            'pagados_count' => $reporte->sum('pagados_count'),
            'pagados_total' => $reporte->sum('pagados_total'),
            'pendientes_count' => $reporte->sum('pendientes_count'),
            'pendientes_total' => $reporte->sum('pendientes_total'),
            'cancelados_count' => $reporte->sum('cancelados_count'),
            'cancelados_total' => $reporte->sum('cancelados_total'),
            'gran_total' => $reporte->sum('pagados_total'),
        ];
    }

    public function getTitle(): string
    {
        $periodo = $this->getPeriodoActivo();

        if ($periodo) {
            return "Corte de Caja - {$periodo->clave}";
        }

        return 'Corte de Caja';
    }

    public function getHeading(): string
    {
        return $this->getTitle();
    }

    public static function can(): bool
    {
        return auth()->user()?->can('ver corte de caja') ?? false;
    }
}

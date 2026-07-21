@php
    $periodoActivo = $this->getPeriodoActivo();
    $reporteActivo = $this->getReporteActivo();
    $totalesActivos = $this->getTotalesActivos();
    $periodos = $this->getPeriodos();
    $reporteHistorial = $this->getReporteHistorial();
    $totalesHistorial = $this->getTotalesHistorial();
    $periodoHistorial = $this->historial_periodo_id ? App\Models\PeriodoEscolar::find($this->historial_periodo_id) : null;
@endphp

<x-filament-panels::page>
    @if ($periodoActivo)
        <x-filament::section>
            <x-slot name="heading">
                Corte de Caja - Periodo Activo: {{ $periodoActivo->clave }}
            </x-slot>

            <div class="text-sm text-gray-500 mb-4">
                {{ $periodoActivo->fecha_inicio->format('d/m/Y') }} - {{ $periodoActivo->fecha_fin->format('d/m/Y') }}
            </div>

            @if ($reporteActivo->isNotEmpty())
                @include('filament.admin.pages._corte-caja-table', ['reporte' => $reporteActivo, 'totales' => $totalesActivos])
            @else
                <p class="text-gray-500">No hay pagos registrados en el periodo activo.</p>
            @endif
        </x-filament::section>
    @else
        <x-filament::section>
            <p class="text-gray-500">No hay un periodo activo. Activa un periodo escolar para ver el corte de caja.</p>
        </x-filament::section>
    @endif

    <x-filament::section>
        <x-slot name="heading">
            Historial de Cortes de Caja
        </x-slot>

        <div class="max-w-xs mb-4">
            <select
                wire:model.live="historial_periodo_id"
                class="block w-full rounded-lg border-gray-300 bg-white px-3 py-2 text-sm shadow-sm focus:border-primary-500 focus:ring-primary-500 dark:border-gray-600 dark:bg-gray-800 dark:text-white"
            >
                <option value="">Seleccionar periodo</option>
                @foreach ($periodos as $p)
                    <option value="{{ $p->id }}">
                        {{ $p->clave }} ({{ $p->fecha_inicio->format('d/m/Y') }} - {{ $p->fecha_fin->format('d/m/Y') }})
                    </option>
                @endforeach
            </select>
        </div>

        @if ($periodoHistorial && $reporteHistorial->isNotEmpty())
            <div class="text-sm text-gray-500 mb-4">
                {{ $periodoHistorial->clave }}: {{ $periodoHistorial->fecha_inicio->format('d/m/Y') }} - {{ $periodoHistorial->fecha_fin->format('d/m/Y') }}
            </div>
            @include('filament.admin.pages._corte-caja-table', ['reporte' => $reporteHistorial, 'totales' => $totalesHistorial])
        @elseif ($this->historial_periodo_id)
            <p class="text-gray-500">No hay pagos registrados en este periodo.</p>
        @endif
    </x-filament::section>
</x-filament-panels::page>

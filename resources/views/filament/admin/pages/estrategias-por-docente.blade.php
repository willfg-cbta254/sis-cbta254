@php
    $docentes = $this->getDocentes();
    $periodos = $this->getPeriodos();
@endphp

<x-filament-panels::page>
    <div class="max-w-xs mb-4">
        <select
            wire:model.live="periodo_id"
            class="block w-full rounded-lg border-gray-300 bg-white px-3 py-2 text-sm shadow-sm focus:border-primary-500 focus:ring-primary-500 dark:border-gray-600 dark:bg-gray-800 dark:text-white"
        >
            <option value="">Todos los periodos</option>
            @foreach ($periodos as $p)
                <option value="{{ $p->id }}">
                    {{ $p->clave }} ({{ $p->fecha_inicio->format('d/m/Y') }} - {{ $p->fecha_fin->format('d/m/Y') }})
                </option>
            @endforeach
        </select>
    </div>

    @forelse ($docentes as $docente)
        <x-filament::section>
            <x-slot name="heading">
                <div class="flex items-center justify-between w-full">
                    <div>
                        <span class="font-semibold">{{ $docente['nombre'] }}</span>
                        <span class="ml-2 text-sm text-gray-500">({{ $docente['numero_empleado'] }})</span>
                    </div>
                    <div class="text-sm text-gray-500">
                        {{ $docente['total_estrategias'] }} estrategia(s)
                    </div>
                </div>
            </x-slot>

            @forelse ($docente['materias'] as $materia)
                <div class="mb-4 last:mb-0">
                    <div class="flex items-center gap-2 mb-2">
                        <x-filament::badge icon="heroicon-m-book-open" color="primary">
                            {{ $materia['nombre'] }}
                        </x-filament::badge>
                        <span class="text-xs text-gray-400">({{ $materia['items']->count() }})</span>
                    </div>

                    <div class="overflow-hidden rounded-lg border border-gray-200 dark:border-gray-700">
                        <table class="w-full text-sm">
                            <thead>
                                <tr class="bg-gray-50 dark:bg-gray-800/50">
                                    <th class="px-4 py-2 text-left font-medium text-gray-600 dark:text-gray-400">Título</th>
                                    <th class="px-4 py-2 text-left font-medium text-gray-600 dark:text-gray-400 w-32">Periodo</th>
                                    <th class="px-4 py-2 text-center font-medium text-gray-600 dark:text-gray-400 w-20">Archivo</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                                @foreach ($materia['items'] as $estrategia)
                                    <tr class="hover:bg-gray-50 dark:hover:bg-gray-800/30">
                                        <td class="px-4 py-2">
                                            <div class="font-medium">{{ $estrategia->titulo }}</div>
                                            @if ($estrategia->descripcion)
                                                <div class="text-xs text-gray-500 mt-0.5 line-clamp-2">{{ $estrategia->descripcion }}</div>
                                            @endif
                                        </td>
                                        <td class="px-4 py-2 text-gray-500">
                                            <x-filament::badge color="gray">
                                                {{ $estrategia->periodoEscolar?->clave }}
                                            </x-filament::badge>
                                        </td>
                                        <td class="px-4 py-2 text-center">
                                            <a
                                                href="{{ route('estrategias.stream', $estrategia) }}"
                                                target="_blank"
                                                class="inline-flex items-center justify-center rounded-md p-2 text-primary-600 hover:bg-primary-50 dark:hover:bg-primary-500/10"
                                                title="Ver archivo"
                                            >
                                                <x-filament::icon
                                                    icon="{{ str_ends_with($estrategia->archivo, '.pdf') ? 'heroicon-o-document' : 'heroicon-o-document-text' }}"
                                                    class="w-5 h-5"
                                                />
                                            </a>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            @empty
                <p class="text-sm text-gray-500">Sin estrategias registradas.</p>
            @endforelse
        </x-filament::section>
    @empty
        <x-filament::section>
            <p class="text-gray-500">No hay docentes con estrategias didácticas
                @if ($this->periodo_id)
                    en el periodo seleccionado.
                @else
                    registradas en el sistema.
                @endif
            </p>
        </x-filament::section>
    @endforelse
</x-filament-panels::page>

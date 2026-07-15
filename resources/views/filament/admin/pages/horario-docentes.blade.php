<x-filament-panels::page>
    <div class="space-y-6">
        {{ $this->form }}

        @if($profesor_id && filled($horarioPorDia))
            <div class="grid grid-cols-1 gap-4 lg:grid-cols-5">
                @foreach($dias as $dia)
                    <div class="rounded-xl border border-gray-200 bg-white shadow-sm dark:border-gray-700 dark:bg-gray-800">
                        <div class="border-b border-gray-200 px-4 py-3 dark:border-gray-700">
                            <h3 class="text-center text-lg font-bold text-gray-800 dark:text-gray-200">{{ $dia }}</h3>
                        </div>
                        <div class="space-y-2 p-3">
                            @php $clases = collect($horarioPorDia[$dia] ?? []); @endphp
                            @forelse($clases as $horario)
                                <div class="rounded-lg border border-primary-200 bg-primary-50 p-3 dark:border-primary-800 dark:bg-primary-900/20">
                                    <div class="mb-1 flex items-center justify-between">
                                        <span class="text-xs font-semibold text-primary-600 dark:text-primary-400">
                                            {{ \Carbon\Carbon::parse($horario['hora_inicio'])->format('H:i') }} - {{ \Carbon\Carbon::parse($horario['hora_fin'])->format('H:i') }}
                                        </span>
                                        @if($horario['aula'])
                                            <span class="text-xs text-gray-500 dark:text-gray-400">
                                                <x-heroicon-m-map-pin class="inline size-3" /> {{ $horario['aula'] }}
                                            </span>
                                        @endif
                                    </div>
                                    <p class="text-sm font-semibold text-gray-800 dark:text-gray-200">
                                        {{ $horario['carga_academica']['materia']['nombre'] ?? 'N/A' }}
                                    </p>
                                    <p class="text-xs text-gray-500 dark:text-gray-400">
                                        Grupo: <span class="font-medium">{{ $horario['carga_academica']['grupo']['nombre'] ?? 'N/A' }}</span>
                                    </p>
                                </div>
                            @empty
                                <p class="py-4 text-center text-sm text-gray-400">Sin clases</p>
                            @endforelse
                        </div>
                    </div>
                @endforeach
            </div>
        @elseif($profesor_id)
            <div class="flex flex-col items-center justify-center py-12 text-gray-400">
                <x-heroicon-o-clock class="size-16 mb-4" />
                <p class="text-lg font-medium">Sin horario asignado</p>
                <p class="text-sm">Este docente no tiene horario registrado.</p>
            </div>
        @else
            <div class="flex flex-col items-center justify-center py-12 text-gray-400">
                <x-heroicon-o-users class="size-16 mb-4" />
                <p class="text-lg font-medium">Selecciona un docente</p>
                <p class="text-sm">Elige un docente del listado para visualizar su horario semanal.</p>
            </div>
        @endif
    </div>
</x-filament-panels::page>

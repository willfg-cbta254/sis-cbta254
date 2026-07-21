<x-filament-panels::page>
    @if ($selectedProfesorId)

        <div class="flex gap-2">
            <x-filament::button
                color="gray"
                icon="heroicon-o-arrow-left"
                wire:click="backToProfesors"
                tag="button"
            >
                Todos los profesores
            </x-filament::button>
        </div>

        {{ $this->table }}

    @else

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
            @foreach ($profesors as $profesor)
                <div
                    wire:key="profesor-{{ $profesor['id'] }}"
                    wire:click="selectProfesor({{ $profesor['id'] }})"
                    class="relative flex flex-col items-center text-center p-6 rounded-xl bg-white shadow-sm border-2 border-gray-200 hover:border-primary-500 hover:shadow-md hover:bg-primary-50/50 cursor-pointer transition-all duration-200 group"
                >
                    <div class="w-14 h-14 rounded-full bg-gradient-to-br from-primary-400 to-primary-600 flex items-center justify-center shadow-sm mb-4 group-hover:scale-110 transition-transform">
                        <x-filament::icon
                            icon="heroicon-o-user"
                            class="w-7 h-7 text-white"
                        />
                    </div>

                    <h3 class="text-lg font-bold text-gray-900 leading-tight">
                        {{ $profesor['name'] }}
                    </h3>

                    @if ($profesor['numero_empleado'])
                        <p class="text-sm text-gray-400 mt-0.5">
                            {{ $profesor['numero_empleado'] }}
                        </p>
                    @endif

                    <div class="flex items-center gap-2 mt-4">
                        <x-filament::badge
                            icon="heroicon-o-clock"
                            color="primary"
                            size="sm"
                        >
                            {{ $profesor['horarios_count'] }} horarios
                        </x-filament::badge>
                        <x-filament::badge
                            icon="heroicon-o-book-open"
                            color="info"
                            size="sm"
                        >
                            {{ $profesor['materias_count'] }} materias
                        </x-filament::badge>
                    </div>

                    @if ($profesor['asignaciones']->isNotEmpty())
                        <div class="w-full mt-4 pt-4 border-t border-gray-100">
                            <p class="text-xs font-semibold text-gray-500 uppercase tracking-wider mb-2">
                                Asignaciones
                            </p>
                            <div class="space-y-1.5">
                                @foreach ($profesor['asignaciones'] as $asignacion)
                                    <div class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-md bg-gray-50 text-xs text-gray-700 font-medium">
                                        <x-filament::icon
                                            icon="heroicon-o-chevron-right"
                                            class="w-3 h-3 text-primary-500 flex-shrink-0"
                                        />
                                        {{ $asignacion }}
                                    </div>
                                    @if (!$loop->last)
                                        <br>
                                    @endif
                                @endforeach
                            </div>
                        </div>
                    @endif

                    <div class="mt-auto pt-4 w-full">
                        <span class="inline-flex items-center gap-1.5 text-sm font-medium text-primary-600 group-hover:text-primary-700 transition-colors">
                            Ver horario
                            <x-filament::icon
                                icon="heroicon-o-arrow-right"
                                class="w-4 h-4 group-hover:translate-x-0.5 transition-transform"
                            />
                        </span>
                    </div>
                </div>
            @endforeach
        </div>

        @if ($profesors->isEmpty())
            <div class="text-center py-12">
                <x-filament::icon
                    :name="$icon"
                    class="w-16 h-16 mx-auto text-gray-300"
                />

                <p class="mt-4 text-lg font-medium text-gray-500">
                    No hay profesores con horarios
                </p>

                <p class="mt-1 text-sm text-gray-400">
                    Asigna horarios a los profesores para que aparezcan aquí.
                </p>
            </div>
        @endif

    @endif
</x-filament-panels::page>

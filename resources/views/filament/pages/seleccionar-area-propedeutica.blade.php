<x-filament-panels::page>
    @php
        $alumno = $this->getAlumno();
        $areaSeleccionada = $this->getAreaSeleccionada();
        $areas = $areaSeleccionada ? collect() : $this->getAreas();
    @endphp

    @if (! $alumno?->puedeSeleccionarArea())

        <div class="text-center py-12">
            <x-filament::icon
                name="heroicon-o-academic-cap"
                class="w-16 h-16 mx-auto text-gray-300"
            />

            <p class="mt-4 text-lg font-medium text-gray-500">
                Selección no disponible
            </p>

            <p class="mt-1 text-sm text-gray-400">
                La selección del área propedéutica está disponible únicamente para alumnos de quinto semestre.
            </p>
        </div>

    @elseif ($areaSeleccionada)

        <x-filament::section>
            <x-slot name="heading">
                Tu área propedéutica
            </x-slot>

            <div class="flex flex-col gap-4">
                <div class="flex items-center gap-3">
                    <x-filament::badge color="success" icon="heroicon-o-academic-cap">
                        {{ $areaSeleccionada->nombre }}
                    </x-filament::badge>
                </div>

                @if ($areaSeleccionada->descripcion)
                    <p class="text-sm text-gray-600 dark:text-gray-400">
                        {{ $areaSeleccionada->descripcion }}
                    </p>
                @endif

                @if ($areaSeleccionada->materias->isNotEmpty())
                    <div>
                        <p class="text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">
                            Materias del área
                        </p>

                        <ul class="list-disc list-inside text-sm text-gray-600 dark:text-gray-400 space-y-1">
                            @foreach ($areaSeleccionada->materias->sortBy('semestre_id') as $materia)
                                <li>
                                    {{ $materia->nombre }}
                                    <span class="text-gray-400">
                                        ({{ ucfirst($materia->semestre_id?->value ?? '') }})
                                    </span>
                                </li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <p class="text-xs text-gray-400">
                    Para modificar tu área acude a Servicios Escolares.
                </p>
            </div>
        </x-filament::section>

    @else

        <x-filament::section>
            <x-slot name="heading">
                Selecciona tu área propedéutica
            </x-slot>

            <p class="text-sm text-gray-500 dark:text-gray-400 mb-4">
                Debes seleccionar un área propedéutica de forma obligatoria. Una vez asignada, solo Servicios Escolares puede modificarla.
            </p>

            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
                @foreach ($areas as $area)
                    @php
                        $disponibles = $area->lugaresDisponibles();
                    @endphp

                    <div
                        wire:key="area-{{ $area->id }}"
                        class="relative flex flex-col p-5 rounded-2xl bg-white shadow-md border border-gray-200 dark:bg-gray-900 dark:border-gray-700"
                    >
                        <div class="flex items-center justify-between gap-2">
                            <h3 class="text-lg font-bold text-gray-900 dark:text-white">
                                {{ $area->nombre }}
                            </h3>

                            <x-filament::badge :color="$disponibles > 0 ? 'success' : 'danger'">
                                {{ $disponibles }} disponibles
                            </x-filament::badge>
                        </div>

                        @if ($area->descripcion)
                            <p class="mt-2 text-sm text-gray-500 dark:text-gray-400">
                                {{ $area->descripcion }}
                            </p>
                        @endif

                        @if ($area->materias->isNotEmpty())
                            <ul class="mt-3 list-disc list-inside text-xs text-gray-500 dark:text-gray-400 space-y-1">
                                @foreach ($area->materias->sortBy('semestre_id') as $materia)
                                    <li>{{ $materia->nombre }} ({{ ucfirst($materia->semestre_id?->value ?? '') }})</li>
                                @endforeach
                            </ul>
                        @endif

                        <div class="mt-4">
                            @if ($disponibles > 0)
                                <x-filament::button
                                    color="primary"
                                    icon="heroicon-o-check"
                                    wire:click="seleccionarArea({{ $area->id }})"
                                    wire:confirm="¿Confirmas tu selección del área {{ $area->nombre }}?"
                                    tag="button"
                                    class="w-full"
                                >
                                    Seleccionar
                                </x-filament::button>
                            @else
                                <x-filament::button
                                    color="gray"
                                    disabled
                                    tag="button"
                                    class="w-full"
                                >
                                    Sin cupo
                                </x-filament::button>
                            @endif
                        </div>
                    </div>
                @endforeach
            </div>

            @if ($areas->isEmpty())
                <p class="text-gray-500">Aún no hay áreas propedéuticas disponibles.</p>
            @endif
        </x-filament::section>

    @endif
</x-filament-panels::page>

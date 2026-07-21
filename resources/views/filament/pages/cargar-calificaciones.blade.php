@php
    $groups = $this->getGroups();
    $materias = $this->getMaterias();
@endphp

<x-filament-panels::page>
    @if ($selectedGroupId && $materias->isNotEmpty() && !$selectedMateriaId)

        <x-filament::button
            color="gray"
            icon="heroicon-o-arrow-left"
            wire:click="backToGroups"
            tag="button"
        >
            Todos los grupos
        </x-filament::button>

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-4">
            @foreach ($materias as $materia)
                <div
                    wire:key="materia-{{ $materia->id }}"
                    wire:click="selectMateria({{ $materia->id }})"
                    class="relative flex flex-col items-center justify-center p-8 rounded-2xl bg-white shadow-md border border-gray-200 hover:shadow-lg hover:border-primary-500 hover:bg-primary-50/50 cursor-pointer transition-all duration-200 group"
                >
                    <div class="text-4xl mb-3 text-primary-600 group-hover:scale-110 transition-transform">
                        <x-filament::icon
                            icon="heroicon-o-book-open"
                            class="w-12 h-12 text-primary-600"
                        />
                    </div>

                    <h3 class="text-lg font-bold text-gray-900 text-center">
                        {{ $materia->nombre }}
                    </h3>

                    @if ($materia->clave)
                        <span class="mt-1 text-xs text-gray-400">
                            {{ $materia->clave }}
                        </span>
                    @endif
                </div>
            @endforeach
        </div>

    @elseif ($selectedGroupId)

        <div class="flex gap-2">
            <x-filament::button
                color="gray"
                icon="heroicon-o-arrow-left"
                wire:click="backToGroups"
                tag="button"
            >
                Todos los grupos
            </x-filament::button>

            @if ($materias->isNotEmpty())
                <x-filament::button
                    color="gray"
                    icon="heroicon-o-arrow-left"
                    wire:click="backToMaterias"
                    tag="button"
                >
                    Todas las materias
                </x-filament::button>
            @endif
        </div>

        <form wire:submit="save">
            @if (count($this->calificaciones))
                <div class="pt-6">
                    <x-filament::section>
                        <x-slot name="heading">
                            Calificaciones
                            <span class="text-sm font-normal text-gray-500">
                                ({{ count($this->calificaciones) }} estudiantes)
                            </span>
                        </x-slot>

                        <div class="overflow-x-auto">
                            <table class="w-full text-left table-auto min-w-[1000px]">
                                <thead>
                                    <tr class="bg-gray-100 dark:bg-gray-800">
                                        <th class="sticky left-0 z-10 bg-gray-100 dark:bg-gray-800 px-4 py-2.5 text-left text-xs font-semibold uppercase tracking-wider text-gray-600 dark:text-gray-400 w-12">No.</th>
                                        <th class="sticky left-12 z-10 bg-gray-100 dark:bg-gray-800 px-4 py-2.5 text-left text-xs font-semibold uppercase tracking-wider text-gray-600 dark:text-gray-400 min-w-[200px]">Estudiante</th>
                                        <th class="px-3 py-2.5 text-center text-xs font-semibold uppercase tracking-wider text-gray-600 dark:text-gray-400 w-[85px]">P1</th>
                                        <th class="px-3 py-2.5 text-center text-xs font-semibold uppercase tracking-wider text-gray-600 dark:text-gray-400 w-[85px]">P2</th>
                                        <th class="px-3 py-2.5 text-center text-xs font-semibold uppercase tracking-wider text-gray-600 dark:text-gray-400 w-[85px]">P3</th>
                                        <th class="px-3 py-2.5 text-center text-xs font-semibold uppercase tracking-wider text-gray-600 dark:text-gray-400 w-[85px]">Prom</th>
                                        <th class="px-3 py-2.5 text-center text-xs font-semibold uppercase tracking-wider text-gray-600 dark:text-gray-400 w-[85px]">Extra</th>
                                        <th class="px-3 py-2.5 text-center text-xs font-semibold uppercase tracking-wider text-gray-600 dark:text-gray-400 w-[85px]">C.Int</th>
                                        <th class="px-3 py-2.5 text-center text-xs font-semibold uppercase tracking-wider text-gray-600 dark:text-gray-400 w-[90px]">Recursa</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                                    @foreach ($this->calificaciones as $index => $item)
                                        @php
                                            $p1 = (float) ($item['parcial1'] ?? 0);
                                            $p2 = (float) ($item['parcial2'] ?? 0);
                                            $p3 = (float) ($item['parcial3'] ?? 0);
                                            $prom = $p1 || $p2 || $p3 ? round(($p1 + $p2 + $p3) / 3, 2) : null;
                                        @endphp
                                        <tr class="hover:bg-gray-50 dark:hover:bg-gray-800/50 transition-colors">
                                            <td class="sticky left-0 z-10 bg-white dark:bg-gray-900/50 px-4 py-2 text-gray-500 dark:text-gray-400 text-sm">{{ $loop->iteration }}</td>
                                            <td class="sticky left-12 z-10 bg-white dark:bg-gray-900/50 px-4 py-2 text-sm font-medium text-gray-900 dark:text-white truncate max-w-[200px]">{{ $item['alumno_nombre'] }}</td>
                                            <td class="px-2 py-1.5">
                                                <input type="number" step="0.1" min="0" max="10"
                                                    wire:model.live="calificaciones.{{ $index }}.parcial1"
                                                    class="w-full text-center text-sm border-gray-300 rounded-md shadow-sm focus:border-primary-500 focus:ring-primary-500 dark:bg-gray-800 dark:border-gray-600 dark:text-white">
                                            </td>
                                            <td class="px-2 py-1.5">
                                                <input type="number" step="0.1" min="0" max="10"
                                                    wire:model.live="calificaciones.{{ $index }}.parcial2"
                                                    class="w-full text-center text-sm border-gray-300 rounded-md shadow-sm focus:border-primary-500 focus:ring-primary-500 dark:bg-gray-800 dark:border-gray-600 dark:text-white">
                                            </td>
                                            <td class="px-2 py-1.5">
                                                <input type="number" step="0.1" min="0" max="10"
                                                    wire:model.live="calificaciones.{{ $index }}.parcial3"
                                                    class="w-full text-center text-sm border-gray-300 rounded-md shadow-sm focus:border-primary-500 focus:ring-primary-500 dark:bg-gray-800 dark:border-gray-600 dark:text-white">
                                            </td>
                                            <td class="px-2 py-1.5 text-center text-sm font-semibold {{ $prom !== null && $prom >= 6 ? 'text-green-600' : ($prom !== null ? 'text-red-600' : 'text-gray-400') }}">
                                                {{ $prom !== null ? number_format($prom, 2) : '-' }}
                                            </td>
                                            <td class="px-2 py-1.5">
                                                <input type="number" step="0.1" min="0" max="10"
                                                    wire:model="calificaciones.{{ $index }}.extraordinario"
                                                    class="w-full text-center text-sm border-gray-300 rounded-md shadow-sm focus:border-primary-500 focus:ring-primary-500 dark:bg-gray-800 dark:border-gray-600 dark:text-white"
                                                    placeholder="-">
                                            </td>
                                            <td class="px-2 py-1.5">
                                                <input type="number" step="0.1" min="0" max="10"
                                                    wire:model="calificaciones.{{ $index }}.curso_intersemestral"
                                                    class="w-full text-center text-sm border-gray-300 rounded-md shadow-sm focus:border-primary-500 focus:ring-primary-500 dark:bg-gray-800 dark:border-gray-600 dark:text-white"
                                                    placeholder="-">
                                            </td>
                                            <td class="px-2 py-1.5 text-center">
                                                <input type="checkbox"
                                                    wire:model="calificaciones.{{ $index }}.recursamiento"
                                                    class="rounded border-gray-300 text-primary-600 shadow-sm focus:ring-primary-500 dark:border-gray-600 dark:bg-gray-800">
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </x-filament::section>
                </div>

                <div class="flex gap-3 justify-end items-start mt-4">
                    <x-filament::button type="submit" color="success" size="lg" icon="heroicon-o-check">
                        Guardar Calificaciones
                    </x-filament::button>
                    <x-filament::button tag="a" href="{{ url('/profesor/calificacions') }}" color="gray"
                        size="lg" icon="heroicon-o-x-mark">
                        Cancelar
                    </x-filament::button>
                </div>
            @elseif (filled($this->selectedGroupId))
                <div class="mt-6">
                    <x-filament::section>
                        <p class="text-gray-500">No hay alumnos registrados en este grupo.</p>
                    </x-filament::section>
                </div>
            @endif
        </form>

    @else

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-4">
            @foreach ($groups as $group)
                <div
                    wire:key="group-{{ $group->id }}"
                    wire:click="selectGroup({{ $group->id }})"
                    class="relative flex flex-col items-center justify-center p-8 rounded-2xl bg-white shadow-md border border-gray-200 hover:shadow-lg hover:border-primary-500 hover:bg-primary-50/50 cursor-pointer transition-all duration-200 group"
                >
                    <div class="text-4xl mb-3 text-primary-600 group-hover:scale-110 transition-transform">
                        <x-filament::icon
                            icon="heroicon-o-star"
                            class="w-12 h-12 text-primary-600"
                        />
                    </div>

                    <h3 class="text-lg font-bold text-gray-900 text-center">
                        {{ $group->nombre }}
                    </h3>

                    <div class="mt-2 flex items-center gap-4 text-sm text-gray-500">
                        <span>{{ $group->carrera?->nombre ?? 'Sin carrera' }}</span>
                    </div>

                    <div class="mt-1 text-xs text-gray-400">
                        {{ $group->turno }} · {{ ucfirst($group->semestre_id?->value ?? '') }}
                    </div>

                    <div class="mt-3 flex items-center gap-2">
                        <x-filament::badge icon="heroicon-o-users">
                            {{ $group->alumnos_count }} alumnos
                        </x-filament::badge>

                        @if ($group->activo)
                            <x-filament::badge color="success">
                                Activo
                            </x-filament::badge>
                        @endif
                    </div>
                </div>
            @endforeach
        </div>

        @if ($groups->isEmpty())
            <div class="text-center py-12">
                <x-filament::icon
                    name="heroicon-o-star"
                    class="w-16 h-16 mx-auto text-gray-300"
                />

                <p class="mt-4 text-lg font-medium text-gray-500">
                    No tienes grupos asignados
                </p>

                <p class="mt-1 text-sm text-gray-400">
                    No hay grupos activos asignados a tu perfil de profesor.
                </p>
            </div>
        @endif

    @endif
</x-filament-panels::page>

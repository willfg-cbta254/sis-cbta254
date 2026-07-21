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

        {{ $this->table }}

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
                            :icon="$icon"
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
                    :name="$icon"
                    class="w-16 h-16 mx-auto text-gray-300"
                />

                <p class="mt-4 text-lg font-medium text-gray-500">
                    No hay grupos activos
                </p>

                <p class="mt-1 text-sm text-gray-400">
                    {{ $emptyMessage }}
                </p>
            </div>
        @endif

    @endif
</x-filament-panels::page>

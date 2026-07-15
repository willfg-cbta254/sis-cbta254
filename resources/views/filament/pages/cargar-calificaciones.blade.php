<x-filament-panels::page>
    <x-filament-panels::form wire:submit="save">
        {{ $this->form }}

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
        @elseif (filled($this->grupo_id))
            <div class="mt-6">
                <x-filament::section>
                    <p class="text-gray-500">No hay alumnos registrados en este grupo.</p>
                </x-filament::section>
            </div>
        @endif
    </x-filament-panels::form>
</x-filament-panels::page>

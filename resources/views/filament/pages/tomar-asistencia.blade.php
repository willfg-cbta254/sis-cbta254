<x-filament-panels::page>
    <x-filament-panels::form wire:submit="save">
        {{ $this->form }}

        @if (filled($this->grupo_id) && count($this->asistencias))
            <div class="mt-10" style="margin-top: 1.5rem;">
                <x-filament::section>
                    <x-slot name="heading">
                        Alumnos del grupo
                    </x-slot>

                    <div class="overflow-x-auto">
                        <table class="w-full text-sm">
                            <thead>
                                <tr class="border-b dark:border-gray-700">
                                    <th class="px-3 py-2 text-left font-medium">Nombre</th>
                                    <th class="px-3 py-2 text-center font-medium">Presente</th>
                                    <th class="px-3 py-2 text-center font-medium">Ausente</th>
                                    <th class="px-3 py-2 text-center font-medium">Permiso</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($this->asistencias as $index => $item)
                                    <tr class="border-b dark:border-gray-700">
                                        <td class="px-3 py-2" style="font-size: 0.875rem;">{{ $item['nombre'] }}</td>
                                        <td class="px-3 py-2 text-center" style="width:100px; text-align:center;">
                                            <input type="checkbox"
                                                wire:click="toggleAsistencia({{ $index }}, 'presente')"
                                                {{ $item['presente'] ? 'checked' : '' }}
                                                style="width:1.25rem;height:1.25rem;">
                                        </td>
                                        <td class="px-3 py-2 text-center" style="width:100px; text-align:center;">
                                            <input type="checkbox"
                                                wire:click="toggleAsistencia({{ $index }}, 'ausente')"
                                                {{ $item['ausente'] ? 'checked' : '' }}
                                                style="width:1.25rem;height:1.25rem;">
                                        </td>
                                        <td class="px-3 py-2 text-center" style="width:100px; text-align:center;">
                                            <input type="checkbox"
                                                wire:click="toggleAsistencia({{ $index }}, 'permiso')"
                                                {{ $item['permiso'] ? 'checked' : '' }}
                                                style="width:1.25rem;height:1.25rem;">
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </x-filament::section>
            </div>

            <div class="mt-6" style="margin-top: 1rem;">
                <x-filament::button type="submit" color="success" size="lg" icon="heroicon-o-check">
                    Guardar Asistencia
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

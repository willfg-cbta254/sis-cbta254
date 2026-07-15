<x-filament::page>
    <div class="space-y-6">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="text-xl font-semibold text-gray-900 dark:text-white">Respaldos del sistema</h2>
                <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                    Los respaldos incluyen la base de datos completa y se almacenan en el disco local.
                </p>
            </div>
            <div>
                {{ $this->createBackupAction }}
            </div>
        </div>

        <x-filament::section>
            <x-slot name="heading">
                Respaldos disponibles
            </x-slot>

            @php
                $backups = $this->getBackups();
            @endphp

            @if (count($backups) === 0)
                <div class="flex flex-col items-center justify-center py-12 text-center">
                    <x-filament::icon
                        name="heroicon-o-archive-box"
                        class="mx-auto size-16 text-gray-400 dark:text-gray-500"
                    />
                    <h3 class="mt-4 text-lg font-semibold text-gray-900 dark:text-white">
                        No hay respaldos
                    </h3>
                    <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                        Crea tu primer respaldo para proteger la información del sistema.
                    </p>
                </div>
            @else
                <div class="overflow-hidden rounded-lg border border-gray-200 dark:border-gray-700">
                    <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                        <thead class="bg-gray-50 dark:bg-gray-800">
                            <tr>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500 dark:text-gray-400">
                                    Archivo
                                </th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500 dark:text-gray-400">
                                    Tamaño
                                </th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500 dark:text-gray-400">
                                    Fecha
                                </th>
                                <th scope="col" class="px-6 py-3 text-right text-xs font-medium uppercase tracking-wider text-gray-500 dark:text-gray-400">
                                    Acciones
                                </th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200 bg-white dark:divide-gray-700 dark:bg-gray-900">
                            @foreach ($backups as $backup)
                                <tr class="hover:bg-gray-50 dark:hover:bg-gray-800/50">
                                    <td class="whitespace-nowrap px-6 py-4 text-sm font-medium text-gray-900 dark:text-white">
                                        {{ $backup['name'] }}
                                    </td>
                                    <td class="whitespace-nowrap px-6 py-4 text-sm text-gray-500 dark:text-gray-400">
                                        {{ $backup['size_for_humans'] }}
                                    </td>
                                    <td class="whitespace-nowrap px-6 py-4 text-sm text-gray-500 dark:text-gray-400">
                                        {{ \Carbon\Carbon::createFromTimestamp($backup['last_modified'])->format('d/m/Y H:i:s') }}
                                    </td>
                                    <td class="whitespace-nowrap px-6 py-4 text-right text-sm font-medium">
                                        <div class="flex items-center justify-end gap-2">
                                            {{ ($this->downloadBackupAction)(['path' => $backup['path']]) }}
                                            {{ ($this->deleteBackupAction)(['path' => $backup['path']]) }}
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <p class="text-xs text-gray-500 dark:text-gray-400">
                    Total: {{ count($backups) }} respaldo(s)
                </p>
            @endif
        </x-filament::section>
    </div>
</x-filament::page>

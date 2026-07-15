<x-filament-panels::page>
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
        <div class="bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700 p-4">
            <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Total Asignadas</p>
            <p class="text-2xl font-bold tracking-tight">{{ number_format($totalAsignadas, 1) }} h</p>
        </div>
        <div class="bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700 p-4">
            <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Total Cumplidas</p>
            <p class="text-2xl font-bold tracking-tight text-success-600">{{ number_format($totalCumplidas, 1) }} h</p>
        </div>
        <div class="bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700 p-4">
            <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Pendientes por Cumplir</p>
            <p class="text-2xl font-bold tracking-tight {{ $pendientes > 0 ? 'text-danger-600' : 'text-success-600' }}">{{ number_format($pendientes, 1) }} h</p>
        </div>
    </div>

    {{ $this->table }}
</x-filament-panels::page>

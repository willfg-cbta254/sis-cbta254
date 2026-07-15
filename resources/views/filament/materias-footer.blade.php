<div class="mt-4">
    <x-filament::section>
        <x-slot name="heading">
            Resumen de Horas
        </x-slot>
        <div class="flex items-center gap-3 text-sm">
            <span class="text-gray-600 dark:text-gray-400">Total de horas a la semana:</span>
            <span class="text-xl font-bold text-gray-900 dark:text-white">{{ $totalHoras }}</span>
        </div>
    </x-filament::section>
</div>

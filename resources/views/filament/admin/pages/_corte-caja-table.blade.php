<div class="overflow-x-auto">
    <table class="w-full text-left divide-y divide-gray-200 dark:divide-gray-700">
        <thead>
            <tr class="bg-gray-50 dark:bg-gray-800">
                <th class="px-4 py-3 text-xs font-semibold uppercase tracking-wider text-gray-600 dark:text-gray-400">Concepto</th>
                <th class="px-4 py-3 text-center text-xs font-semibold uppercase tracking-wider text-gray-600 dark:text-gray-400">Pagados</th>
                <th class="px-4 py-3 text-right text-xs font-semibold uppercase tracking-wider text-gray-600 dark:text-gray-400">Total Pagado</th>
                <th class="px-4 py-3 text-center text-xs font-semibold uppercase tracking-wider text-gray-600 dark:text-gray-400">Pendientes</th>
                <th class="px-4 py-3 text-right text-xs font-semibold uppercase tracking-wider text-gray-600 dark:text-gray-400">Total Pendiente</th>
                <th class="px-4 py-3 text-center text-xs font-semibold uppercase tracking-wider text-gray-600 dark:text-gray-400">Cancelados</th>
                <th class="px-4 py-3 text-right text-xs font-semibold uppercase tracking-wider text-gray-600 dark:text-gray-400">Total Cancelado</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
            @foreach ($reporte as $item)
                <tr class="hover:bg-gray-50 dark:hover:bg-gray-800/50">
                    <td class="px-4 py-3 text-sm font-medium text-gray-900 dark:text-white">
                        {{ $item['nombre'] }}
                    </td>
                    <td class="px-4 py-3 text-center">
                        <x-filament::badge color="success">
                            {{ $item['pagados_count'] }}
                        </x-filament::badge>
                    </td>
                    <td class="px-4 py-3 text-right text-sm font-semibold text-green-600 dark:text-green-400">
                        ${{ number_format($item['pagados_total'], 2) }}
                    </td>
                    <td class="px-4 py-3 text-center">
                        <x-filament::badge color="warning">
                            {{ $item['pendientes_count'] }}
                        </x-filament::badge>
                    </td>
                    <td class="px-4 py-3 text-right text-sm font-semibold text-yellow-600 dark:text-yellow-400">
                        ${{ number_format($item['pendientes_total'], 2) }}
                    </td>
                    <td class="px-4 py-3 text-center">
                        <x-filament::badge color="danger">
                            {{ $item['cancelados_count'] }}
                        </x-filament::badge>
                    </td>
                    <td class="px-4 py-3 text-right text-sm font-semibold text-red-600 dark:text-red-400">
                        ${{ number_format($item['cancelados_total'], 2) }}
                    </td>
                </tr>
            @endforeach
        </tbody>
        <tfoot>
            <tr class="bg-gray-100 dark:bg-gray-800 font-bold">
                <td class="px-4 py-3 text-sm text-gray-900 dark:text-white">TOTALES</td>
                <td class="px-4 py-3 text-center">
                    <x-filament::badge color="success">
                        {{ $totales['pagados_count'] }}
                    </x-filament::badge>
                </td>
                <td class="px-4 py-3 text-right text-sm text-green-600 dark:text-green-400">
                    ${{ number_format($totales['pagados_total'], 2) }}
                </td>
                <td class="px-4 py-3 text-center">
                    <x-filament::badge color="warning">
                        {{ $totales['pendientes_count'] }}
                    </x-filament::badge>
                </td>
                <td class="px-4 py-3 text-right text-sm text-yellow-600 dark:text-yellow-400">
                    ${{ number_format($totales['pendientes_total'], 2) }}
                </td>
                <td class="px-4 py-3 text-center">
                    <x-filament::badge color="danger">
                        {{ $totales['cancelados_count'] }}
                    </x-filament::badge>
                </td>
                <td class="px-4 py-3 text-right text-sm text-red-600 dark:text-red-400">
                    ${{ number_format($totales['cancelados_total'], 2) }}
                </td>
            </tr>
            <tr class="bg-primary-50 dark:bg-primary-900/20 font-bold">
                <td class="px-4 py-3 text-sm text-gray-900 dark:text-white" colspan="2">
                    INGRESO TOTAL (Pagados)
                </td>
                <td class="px-4 py-3 text-right text-lg text-primary-600 dark:text-primary-400">
                    ${{ number_format($totales['gran_total'], 2) }}
                </td>
                <td colspan="4"></td>
            </tr>
        </tfoot>
    </table>
</div>

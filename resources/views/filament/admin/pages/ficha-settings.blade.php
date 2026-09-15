@php $stats = $this->getStats(); @endphp

<x-filament-panels::page>
    {{-- Hero + estado --}}
    <div class="relative overflow-hidden rounded-2xl border border-gray-200 bg-white shadow-sm dark:border-white/10 dark:bg-gray-900">
        <div class="absolute inset-0 bg-gradient-to-br from-emerald-600 via-green-600 to-teal-700 opacity-95"></div>
        <div class="absolute inset-0 bg-[radial-gradient(circle_at_30%_20%,rgba(255,255,255,0.18),transparent_50%)]"></div>
        <div class="relative flex flex-col gap-6 p-6 md:p-8 lg:flex-row lg:items-center lg:justify-between">
            <div class="flex-1">
                <div class="inline-flex items-center gap-2 rounded-full bg-white/15 px-3 py-1 text-xs font-medium text-white ring-1 ring-white/20 backdrop-blur">
                    <span class="size-2 rounded-full {{ $stats['is_open'] ? 'bg-emerald-300 animate-pulse' : 'bg-red-300' }}"></span>
                    {{ $stats['is_open'] ? 'Registro abierto' : 'Registro cerrado' }}
                </div>
                <h2 class="mt-3 text-2xl font-bold tracking-tight text-white md:text-3xl">Fichas de Nuevo Ingreso</h2>
                <p class="mt-2 max-w-2xl text-sm leading-relaxed text-emerald-50">
                    Ciclo <span class="font-semibold text-white">{{ $stats['ciclo'] }}</span> · Próximo folio
                    <span class="font-mono font-semibold text-white">{{ $stats['next_folio_label'] }}</span>
                    · Las fichas se generan automáticamente al guardar cada aspirante.
                </p>
                <div class="mt-4 flex flex-wrap gap-2">
                    <a href="{{ \App\Filament\Admin\Resources\FichaResource\FichaResource::getUrl('index') }}"
                       class="inline-flex items-center gap-2 rounded-lg bg-white px-4 py-2 text-sm font-semibold text-emerald-700 shadow-sm hover:bg-emerald-50 transition">
                        <x-heroicon-o-document-text class="size-4" /> Ver listado de fichas
                    </a>
                    @if($stats['is_open'])
                        <span class="inline-flex items-center gap-2 rounded-lg bg-emerald-900/30 px-4 py-2 text-sm font-medium text-white ring-1 ring-white/20">
                            <x-heroicon-o-check-circle class="size-4" /> Visible en página principal
                        </span>
                    @endif
                </div>
            </div>
            <div class="hidden lg:block">
                <div class="rounded-2xl bg-white/10 p-4 ring-1 ring-white/20 backdrop-blur">
                    <x-heroicon-o-ticket class="size-14 text-white/90" />
                </div>
            </div>
        </div>
    </div>

    {{-- Stats grid --}}
    <div class="grid grid-cols-1 gap-4 sm:grid-cols-3">
        <div class="rounded-2xl border border-gray-200 bg-white p-5 shadow-sm dark:border-white/10 dark:bg-gray-900">
            <div class="flex items-center justify-between">
                <p class="text-xs font-semibold uppercase tracking-widest text-gray-500 dark:text-gray-400">Fichas · ciclo actual</p>
                <span class="rounded-full bg-emerald-100 p-2 text-emerald-700 dark:bg-emerald-500/20 dark:text-emerald-300">
                    <x-heroicon-o-academic-cap class="size-4" />
                </span>
            </div>
            <p class="mt-3 text-3xl font-bold tracking-tight text-gray-900 dark:text-white">{{ number_format($stats['del_ciclo']) }}</p>
            <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">Ciclo {{ $stats['ciclo'] }}</p>
        </div>

        <div class="rounded-2xl border border-gray-200 bg-white p-5 shadow-sm dark:border-white/10 dark:bg-gray-900">
            <div class="flex items-center justify-between">
                <p class="text-xs font-semibold uppercase tracking-widest text-gray-500 dark:text-gray-400">Total histórico</p>
                <span class="rounded-full bg-blue-100 p-2 text-blue-700 dark:bg-blue-500/20 dark:text-blue-300">
                    <x-heroicon-o-archive-box class="size-4" />
                </span>
            </div>
            <p class="mt-3 text-3xl font-bold tracking-tight text-gray-900 dark:text-white">{{ number_format($stats['total']) }}</p>
            <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">Todas las fichas registradas</p>
        </div>

        <div class="rounded-2xl border border-gray-200 bg-white p-5 shadow-sm dark:border-white/10 dark:bg-gray-900">
            <div class="flex items-center justify-between">
                <p class="text-xs font-semibold uppercase tracking-widest text-gray-500 dark:text-gray-400">Próximo folio</p>
                <span class="rounded-full bg-amber-100 p-2 text-amber-700 dark:bg-amber-500/20 dark:text-amber-300">
                    <x-heroicon-o-hashtag class="size-4" />
                </span>
            </div>
            <p class="mt-3 font-mono text-2xl font-bold tracking-tight text-gray-900 dark:text-white">{{ $stats['next_folio_label'] }}</p>
            <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">Se incrementa automáticamente</p>
        </div>
    </div>

    {{-- Form mejorado --}}
    <div class="[&_.fi-section]:rounded-2xl [&_.fi-section]:shadow-sm">
        {{ $this->form }}
    </div>

    <p class="text-center text-xs text-gray-400 dark:text-gray-500">
        Consejo: usa <kbd class="rounded bg-gray-100 px-1.5 py-0.5 font-mono text-xs dark:bg-white/10">Ctrl + S</kbd> para guardar rápidamente.
    </p>
</x-filament-panels::page>

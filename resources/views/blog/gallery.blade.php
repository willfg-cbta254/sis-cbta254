@extends('layouts.app', ['navbarActive' => 'galeria'])

@section('title', 'Galería | ' . config('app.name', 'CBTA 254'))

@section('meta_description', 'Galería del CBTA 254 - Centro de Bachillerato Tecnológico Agropecuario No. 254.')

@section('content')
    {{-- HERO --}}
    @php $welcome = \App\Models\WelcomeContent::first(); @endphp
    <section class="relative min-h-[60vh] flex items-center pt-16 lg:pt-20">
        <div class="absolute inset-0 z-0">
            <img src="{{ $welcome?->hero_image ? (Str::startsWith($welcome->hero_image, 'http') ? $welcome->hero_image : asset('storage/' . $welcome->hero_image)) : 'https://images.unsplash.com/photo-1588072432836-e10032774350?q=80&w=2072&auto=format&fit=crop' }}"
                alt="" class="size-full object-cover">
            <div class="section-gradient absolute inset-0"></div>
        </div>
        <div class="relative z-10 w-full max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10 md:py-16">
            <div class="text-center">
                <h1 class="text-4xl sm:text-5xl lg:text-6xl font-bold text-white mb-4">Galería</h1>
                <p class="text-lg sm:text-xl text-green-100 max-w-2xl mx-auto">Conoce nuestras instalaciones, eventos y actividades</p>
            </div>
        </div>
    </section>

    {{-- PERIOD / MONTH GROUPED GRID --}}
    @php
        $monthNames = ['Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre'];
        $grouped = $images->groupBy(fn ($item) => $item->periodoEscolar?->clave ?? 'Sin periodo');
    @endphp

    <section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10 lg:py-14">
        @if($images->count())
            @foreach($grouped as $periodoClave => $periodImages)
                @php
                    $byMonth = $periodImages->groupBy(fn ($item) => $item->created_at->format('Y-m'));
                @endphp

                <div class="mb-12 last:mb-0">
                    <h2 class="text-2xl font-bold text-gray-900 mb-2">{{ $periodoClave }}</h2>

                    @foreach($byMonth as $monthKey => $monthImages)
                        @php
                            [$year, $month] = explode('-', $monthKey);
                            $monthLabel = $monthNames[(int) $month - 1] . ' ' . $year;
                        @endphp

                        <div class="mb-8 last:mb-0">
                            <h3 class="text-sm font-semibold text-gray-500 uppercase tracking-wider mb-4">{{ $monthLabel }}</h3>

                            <div class="columns-1 sm:columns-2 lg:columns-3 xl:columns-4 gap-2 space-y-2">
                                @foreach($monthImages as $item)
                                    <div class="break-inside-avoid group relative rounded-lg overflow-hidden bg-white shadow-sm hover:shadow-md transition-shadow cursor-pointer"
                                        onclick="openLightbox('{{ Str::startsWith($item->image, 'http') ? $item->image : asset('storage/' . $item->image) }}', '{{ $item->title ?? '' }}')">
                                        <img src="{{ Str::startsWith($item->image, 'http') ? $item->image : asset('storage/' . $item->image) }}"
                                            alt="{{ $item->title ?? 'Galería CBTA 254' }}"
                                            class="w-full object-cover group-hover:scale-105 transition-transform duration-700">
                                        @if($item->title || $item->description)
                                            <div class="absolute inset-0 bg-gradient-to-t from-black/70 via-transparent to-transparent opacity-0 group-hover:opacity-100 transition-opacity">
                                                <div class="absolute bottom-0 left-0 right-0 p-4">
                                                    @if($item->title)
                                                        <h3 class="text-sm font-bold text-white">{{ $item->title }}</h3>
                                                    @endif
                                                    @if($item->description)
                                                        <p class="text-xs text-white/80 mt-1">{{ $item->description }}</p>
                                                    @endif
                                                </div>
                                            </div>
                                        @endif
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endforeach
                </div>
            @endforeach

            <div class="mt-10">
                {{ $images->links() }}
            </div>
        @else
            <div class="text-center py-20">
                <svg class="size-16 mx-auto text-gray-300 mb-4" fill="none" viewBox="0 0 24 24" stroke-width="1" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="m2.25 15.75 5.159-5.159a2.25 2.25 0 0 1 3.182 0l5.159 5.159m-1.5-1.5 1.409-1.409a2.25 2.25 0 0 1 3.182 0l2.909 2.909M16.5 18.75a7.5 7.5 0 1 1-15 0 7.5 7.5 0 0 1 15 0Z" /></svg>
                <h2 class="text-xl font-bold text-gray-500 mb-2">No hay imágenes aún</h2>
                <p class="text-gray-400">Pronto publicaremos nuevas fotos. ¡Vuelve pronto!</p>
            </div>
        @endif
    </section>
@endsection

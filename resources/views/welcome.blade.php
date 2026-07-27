@php
    $welcome = \App\Models\WelcomeContent::first();

    $slides = $welcome?->hero_slides ?? [];
    if (empty($slides) && $welcome?->hero_title) {
        $slides = [[
            'badge' => $welcome->hero_badge,
            'title' => $welcome->hero_title,
            'description' => $welcome->hero_description,
            'image' => $welcome->hero_image,
            'video' => null,
            'btn_text' => $welcome->hero_btn_text,
            'btn_secondary_text' => $welcome->hero_btn_secondary_text,
        ]];
    }

    $galleryImages = \App\Models\Gallery::where('is_active', true)
        ->whereNotNull('image')
        ->latest()
        ->limit(6)
        ->get();

    $communityAnnouncements = \App\Models\Announcement::published()->where('type', 'community')->orderBy('published_at', 'desc')->limit(6)->get();
@endphp

@extends('layouts.app', ['navbarActive' => ''])

@section('title', $welcome?->meta_title ?? (config('app.name', 'CBTA 254') . ' | Educación Media Superior'))

@section('meta_description', $welcome?->meta_description ?? 'CBTA 254 - Centro de Bachillerato Tecnológico Agropecuario No. 254. Educación Media Superior de Calidad en México.')

@section('content')
    {{-- HERO CAROUSEL --}}
    <section id="inicio" class="relative min-h-[100vh]" x-data="heroCarousel()" x-init="init()">
        <template x-for="(slide, index) in slides" :key="index">
            <div x-show="active === index" x-transition:enter="transition-opacity duration-700"
                class="absolute inset-0 z-0 flex items-center pt-16 lg:pt-20" style="min-height: 100vh;">
                <template x-if="slide.video">
                    <video autoplay muted loop playsinline class="absolute inset-0 size-full object-cover">
                        <source :src="slide.video.startsWith('http') ? slide.video : '/storage/' + slide.video" type="video/mp4">
                    </video>
                </template>
                <template x-if="!slide.video">
                    <img :src="slide.image
                        ? (slide.image.startsWith('http') ? slide.image : '/storage/' + slide.image)
                        : 'https://images.unsplash.com/photo-1588072432836-e10032774350?q=80&w=2072&auto=format&fit=crop'"
                        alt="CBTA 254" class="absolute inset-0 size-full object-cover">
                </template>
                <div class="hero-gradient absolute inset-0"></div>

                <div class="relative z-10 w-full max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10 md:py-16">
                    <div class="max-w-3xl">
                        <template x-if="slide.badge">
                            <span class="inline-block rounded-full bg-white/15 backdrop-blur-sm px-3 py-1 text-xs font-semibold text-amber-300 border border-white/20 mb-3"
                                x-text="slide.badge"></span>
                        </template>

                        <h1 class="text-2xl sm:text-3xl md:text-4xl lg:text-5xl font-extrabold text-white leading-tight tracking-tight"
                            x-html="slide.title || 'CBTA No. 254: Por Una Educación, Científica y Crítica'"></h1>

                        <template x-if="slide.description">
                            <p class="mt-3 sm:mt-4 text-sm sm:text-base md:text-lg text-white/80 max-w-2xl leading-relaxed"
                                x-text="slide.description"></p>
                        </template>

                        <div class="mt-6 sm:mt-8 flex flex-wrap gap-4">
                            <template x-if="slide.btn_text">
                                <a href="#oferta-educativa"
                                    class="inline-flex items-center gap-2 rounded-xl bg-amber-400 px-6 py-3.5 text-sm font-bold text-green-950 shadow-lg shadow-amber-400/30 hover:bg-amber-300 hover:shadow-amber-300/40 transition-all"
                                    x-text="slide.btn_text">
                                </a>
                            </template>
                            <template x-if="slide.btn_secondary_text">
                                <a href="#convocatorias"
                                    class="inline-flex items-center gap-2 rounded-xl border border-white/30 px-6 py-3.5 text-sm font-semibold text-white hover:bg-white/10 transition-all"
                                    x-text="slide.btn_secondary_text">
                                </a>
                            </template>
                        </div>
                    </div>
                </div>
            </div>
        </template>

        {{-- Dots --}}
        <template x-if="slides.length > 1">
            <div class="absolute bottom-6 left-1/2 -translate-x-1/2 z-20 flex gap-2">
                <template x-for="(slide, index) in slides" :key="index">
                    <button @click="goTo(index)"
                        class="size-2.5 rounded-full transition-all duration-300"
                        :class="active === index ? 'bg-white scale-125' : 'bg-white/40 hover:bg-white/60'">
                    </button>
                </template>
            </div>
        </template>
    </section>

    @php
        $fallbackSlide = $slides[0] ?? null;
    @endphp

    @push('scripts')
    <script>
        function heroCarousel() {
            return {
                slides: @js($slides),
                active: 0,
                interval: null,
                init() {
                    if (this.slides.length > 1) {
                        this.interval = setInterval(() => {
                            this.next();
                        }, 6000);
                    }
                },
                next() {
                    this.active = (this.active + 1) % this.slides.length;
                },
                goTo(index) {
                    this.active = index;
                    clearInterval(this.interval);
                    if (this.slides.length > 1) {
                        this.interval = setInterval(() => {
                            this.next();
                        }, 6000);
                    }
                }
            }
        }
    </script>
    @endpush

    @php
        $colorMap = [
            'green' => ['bg' => 'from-green-800/5', 'iconBg' => 'bg-green-100', 'iconBgHover' => 'group-hover:bg-green-800', 'iconColor' => 'text-green-700', 'iconColorHover' => 'group-hover:text-white'],
            'blue' => ['bg' => 'from-blue-800/5', 'iconBg' => 'bg-blue-100', 'iconBgHover' => 'group-hover:bg-blue-900', 'iconColor' => 'text-blue-700', 'iconColorHover' => 'group-hover:text-white'],
            'amber' => ['bg' => 'from-amber-800/5', 'iconBg' => 'bg-amber-100', 'iconBgHover' => 'group-hover:bg-amber-800', 'iconColor' => 'text-amber-700', 'iconColorHover' => 'group-hover:text-white'],
        ];
        $careerIcons = [
            'leaf' => 'M12 21a9.004 9.004 0 0 0 8.716-6.747M12 21a9.004 9.004 0 0 1-8.716-6.747M12 21c2.485 0 4.5-4.03 4.5-9S14.485 3 12 3m0 18c-2.485 0-4.5-4.03-4.5-9S9.515 3 12 3m0 0a8.997 8.997 0 0 1 7.843 4.582M12 3a8.997 8.997 0 0 0-7.843 4.582m15.686 0A11.953 11.953 0 0 1 12 10.5c-2.998 0-5.74-1.1-7.843-2.918m15.686 0A8.959 8.959 0 0 1 21 12c0 .778-.099 1.533-.284 2.253m0 0A17.919 17.919 0 0 1 12 16.5c-3.162 0-6.133-.815-8.716-2.247m0 0A9.015 9.015 0 0 1 3 12c0-1.605.42-3.113 1.157-4.418',
            'computer' => 'M9 17.25v1.007a3 3 0 0 1-.879 2.122L7.5 21h9l-.621-.621A3 3 0 0 1 15 18.257V17.25m6-12V15a2.25 2.25 0 0 1-2.25 2.25H5.25A2.25 2.25 0 0 1 3 15V5.25m18 0A2.25 2.25 0 0 0 18.75 3H5.25A2.25 2.25 0 0 0 3 5.25m18 0V12a2.25 2.25 0 0 1-2.25 2.25H5.25A2.25 2.25 0 0 1 3 12V5.25',
            'chart' => 'M3.75 3v11.25A2.25 2.25 0 0 0 6 16.5h2.25M3.75 3h-1.5m1.5 0h16.5m0 0h1.5m-1.5 0v11.25A2.25 2.25 0 0 1 18 16.5h-2.25m-7.5 0h7.5m-7.5 0-1 3m8.5-3 1 3m0 0 .5 1.5m-.5-1.5h-9.5m0 0-.5 1.5M9 11.25v1.5M12 9v3.75m3-6v6',
        ];
        $careers = [
            ['title' => 'Técnico en Agropecuario', 'description' => 'Formación en producción agropecuaria, manejo sustentable de recursos y tecnología aplicada al campo.', 'icon' => 'leaf', 'color' => 'green'],
            ['title' => 'Técnico en Ofimática', 'description' => 'Competencias digitales, administración documental y gestión de tecnologías de la información.', 'icon' => 'computer', 'color' => 'blue'],
            ['title' => 'Técnico en Administración de Emprendimientos', 'description' => 'Habilidades directivas, creación de proyectos productivos y gestión empresarial.', 'icon' => 'chart', 'color' => 'amber'],
        ];
    @endphp

    {{-- OFERTA EDUCATIVA --}}
    <section id="oferta-educativa" class="py-10 sm:py-12 lg:py-16 bg-gray-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center max-w-2xl mx-auto mb-8 sm:mb-10">
                <span class="inline-block rounded-full bg-green-100 px-4 py-1.5 text-xs font-semibold text-green-800 mb-3">
                    Oferta Educativa
                </span>
                <h2 class="text-2xl sm:text-3xl lg:text-4xl font-bold text-gray-900">
                    Carreras Técnicas
                </h2>
                <p class="mt-3 text-gray-500 leading-relaxed">
                    Formación integral con planes de estudio actualizados y prácticas profesionales en el sector.
                </p>
            </div>

            <div class="grid sm:grid-cols-2 lg:grid-cols-3 gap-5 lg:gap-6">
                @foreach($careers as $career)
                    @php $c = $colorMap[$career['color']] ?? $colorMap['green']; @endphp
                    <div class="group relative rounded-2xl bg-white p-5 sm:p-6 shadow-sm hover:shadow-lg transition-all duration-300 hover:-translate-y-1">
                        <div class="absolute inset-0 rounded-2xl bg-gradient-to-b {{ $c['bg'] }} to-transparent opacity-0 group-hover:opacity-100 transition-opacity"></div>
                        <div class="relative">
                            <div class="size-14 rounded-xl {{ $c['iconBg'] }} flex items-center justify-center mb-5 {{ $c['iconBgHover'] }} transition-colors duration-300">
                                <svg class="size-7 {{ $c['iconColor'] }} {{ $c['iconColorHover'] }} transition-colors duration-300"
                                    fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="{{ $careerIcons[$career['icon']] }}" />
                                </svg>
                            </div>
                            <h3 class="text-lg font-bold text-gray-900 mb-2">{{ $career['title'] }}</h3>
                            <p class="text-sm text-gray-500 leading-relaxed">{{ $career['description'] }}</p>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    {{-- GALERÍA --}}
    <section id="galeria" class="py-10 sm:py-12 lg:py-16 bg-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center max-w-2xl mx-auto mb-8 sm:mb-10">
                <span class="inline-block rounded-full bg-blue-100 px-4 py-1.5 text-xs font-semibold text-blue-800 mb-3">
                    Conoce el CBTA 254
                </span>
                <h2 class="text-2xl sm:text-3xl lg:text-4xl font-bold text-gray-900">
                    GALERÍA
                </h2>
                <p class="mt-3 text-gray-500 leading-relaxed">
                    Espacios diseñados para el aprendizaje teórico y práctico de nuestros estudiantes.
                </p>
            </div>

            <div class="grid sm:grid-cols-2 lg:grid-cols-3 gap-3 sm:gap-0">
                @foreach($galleryImages as $item)
                    <div class="relative group overflow-hidden aspect-[4/3] cursor-pointer"
                        onclick="openLightbox('{{ Str::startsWith($item->image, 'http') ? $item->image : asset('storage/' . $item->image) }}', '{{ $item->title ?? '' }}')">
                        <img src="{{ Str::startsWith($item->image, 'http') ? $item->image : asset('storage/' . $item->image) }}"
                            alt="{{ $item->title ?? '' }}"
                            class="size-full object-cover group-hover:scale-105 transition-transform duration-700">
                        <div class="absolute inset-0 bg-gradient-to-t from-black/60 via-transparent to-transparent"></div>
                        <div class="absolute bottom-0 left-0 right-0 p-4 sm:p-6">
                            <span class="text-sm font-semibold text-white">{{ $item->title ?? '' }}</span>
                        </div>
                    </div>
                @endforeach
            </div>
            <div class="text-center mt-8">
                <a href="{{ route('blog.gallery') }}"
                    class="inline-flex items-center gap-2 rounded-xl bg-green-800 px-6 py-3 text-sm font-semibold text-white hover:bg-green-700 transition-colors">
                    Ver galería completa
                    <svg class="size-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5 21 12m0 0-7.5 7.5M21 12H3" /></svg>
                </a>
            </div>
        </div>
    </section>

    {{-- CONVOCATORIAS / AVISOS --}}
    <section id="convocatorias" class="py-10 sm:py-12 lg:py-16 bg-gray-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center max-w-2xl mx-auto mb-8 sm:mb-10">
                <span class="inline-block rounded-full bg-amber-100 px-4 py-1.5 text-xs font-semibold text-amber-800 mb-3">
                    Avisos
                </span>
                <h2 class="text-2xl sm:text-3xl lg:text-4xl font-bold text-gray-900">
                    Avisos a la Comunidad Estudiantil
                </h2>
            </div>

            <div class="grid sm:grid-cols-2 lg:grid-cols-3 gap-5 lg:gap-6">
                @foreach($communityAnnouncements as $announcement)
                    <div class="rounded-2xl bg-white overflow-hidden shadow-sm hover:shadow-md transition-shadow cursor-pointer"
                        onclick="openAvisoModal(this)"
                        data-title="{{ $announcement->title }}"
                        data-content="{{ base64_encode($announcement->content) }}"
                        data-date="{{ $announcement->published_at?->isoFormat('D MMMM, YYYY') }}">
                        <div class="h-1.5 bg-gradient-to-r from-green-700 to-green-500"></div>
                        <div class="p-5 sm:p-6">
                            <span class="inline-block rounded-full bg-green-100 text-green-700 px-3 py-1 text-xs font-semibold mb-3">Comunidad</span>
                            <h3 class="text-base font-bold text-gray-900 mb-2">{{ $announcement->title }}</h3>
                            <div class="text-sm text-gray-500 leading-relaxed mb-3 text-justify">{!! Str::limit(strip_tags($announcement->content), 200) !!}</div>
                            <time class="text-xs text-gray-400">Publicado: {{ $announcement->published_at?->isoFormat('D MMMM, YYYY') }}</time>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    {{-- LOGIN PORTAL (comentado) --}}
    {{--
    <section id="contacto" class="py-10 sm:py-12 lg:py-16 bg-white">
        ...
    </section>
    --}}

    @livewire('registro-ficha')
@endsection

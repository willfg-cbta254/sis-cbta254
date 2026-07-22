@php
    $welcome = \App\Models\WelcomeContent::first();

    $galleryImages = \App\Models\Gallery::where('is_active', true)
        ->whereNotNull('image')
        ->latest()
        ->limit(6)
        ->get();

    $communityAnnouncements = \App\Models\Announcement::published()->where('type', 'community')->orderBy('published_at', 'desc')->limit(6)->get();
    $fichaOpen = \App\Models\FichaSetting::first()?->is_open ?? false;
@endphp

<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description"
        content="{{ $welcome?->meta_description ?? 'CBTA 254 - Centro de Bachillerato Tecnológico Agropecuario No. 254. Educación Media Superior de Calidad en México.' }}">
    <title>{{ $welcome?->meta_title ?? config('app.name', 'CBTA 254') . ' | Educación Media Superior' }}</title>
    @fonts
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        html {
            scroll-behavior: smooth;
        }

        .hero-gradient {
            background: linear-gradient(135deg, rgba(13, 79, 41, 0.92) 0%, rgba(30, 58, 95, 0.88) 80%);
        }

        [x-cloak] {
            display: none !important;
        }
    </style>

    @livewireStyles
</head>

<body class="min-h-screen bg-white text-gray-900 antialiased">

    {{-- ============================================================ --}}
    {{--  NAVBAR                                                       --}}
    {{-- ============================================================ --}}
    <nav id="navbar" class="fixed top-0 inset-x-0 z-50 bg-white/95 backdrop-blur border-b border-gray-200">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between h-16 lg:h-20">

                {{-- Logo --}}
                <a href="{{ url('/') }}" class="flex items-center gap-2.5 group">
                    <div
                        class="size-9 rounded-lg bg-gradient-to-br from-green-800 to-blue-900 flex items-center justify-center text-white font-bold text-sm shadow-sm">
                        CBTA
                    </div>
                    <div class="hidden sm:block">
                        <span class="text-sm font-bold text-gray-900 group-hover:text-green-800 transition-colors">CBTA No.
                            254</span>
                        <span class="block text-[10px] leading-tight text-gray-500">Centro de Bachillerato Tecnológico Agropecuario No. 254</span>
                    </div>
                </a>

                {{-- Desktop Nav --}}
                <div class="hidden lg:flex items-center gap-8">
                    <a href="#inicio"
                        class="text-sm font-medium text-gray-600 hover:text-green-800 transition-colors">Inicio</a>
                    <a href="#oferta-educativa"
                        class="text-sm font-medium text-gray-600 hover:text-green-800 transition-colors">Oferta
                        Educativa</a>
                    <a href="#galeria"
                        class="text-sm font-medium text-gray-600 hover:text-green-800 transition-colors">Galería</a>
                    <a href="#convocatorias"
                        class="text-sm font-medium text-gray-600 hover:text-green-800 transition-colors">Convocatorias</a>
                    <a href="#contacto"
                        class="text-sm font-medium text-gray-600 hover:text-green-800 transition-colors">Contacto</a>
                    <a href="{{ route('blog.index') }}"
                        class="text-sm font-medium text-gray-600 hover:text-green-800 transition-colors">Blog</a>
                </div>

                {{-- Auth Buttons --}}
                <div class="hidden lg:flex items-center gap-3">
                    @if ($fichaOpen)
                        <button type="button" onclick="Livewire.dispatch('openFichaModal')"
                            class="inline-flex items-center gap-1.5 rounded-lg border border-amber-400/60 bg-amber-50 px-4 py-2 text-sm font-semibold text-amber-800 hover:bg-amber-100 transition-colors cursor-pointer">
                            <svg class="size-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                            </svg>
                            Aspirantes / Pre-registro
                        </button>
                    @endif

                    @guest
                        <a href="{{ url('/alumno/login') }}"
                            class="inline-flex items-center gap-1.5 rounded-lg bg-gradient-to-r from-green-800 to-blue-900 px-5 py-2 text-sm font-semibold text-white shadow-sm hover:from-green-700 hover:to-blue-800 transition-all">
                            <svg class="size-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M15.75 9V5.25A2.25 2.25 0 0 0 13.5 3h-6a2.25 2.25 0 0 0-2.25 2.25v13.5A2.25 2.25 0 0 0 7.5 21h6a2.25 2.25 0 0 0 2.25-2.25V15m3 0 3-3m0 0-3-3m3 3H9" />
                            </svg>
                            Iniciar Sesión
                        </a>
                    @else
                        <a href="{{ url('/alumno') }}"
                            class="inline-flex items-center gap-1.5 rounded-lg bg-gradient-to-r from-green-800 to-blue-900 px-5 py-2 text-sm font-semibold text-white shadow-sm hover:from-green-700 hover:to-blue-800 transition-all">
                            <svg class="size-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M9.594 3.94c.09-.542.56-.94 1.11-.94h2.593c.55 0 1.02.398 1.11.94l.213 1.281c.063.374.313.686.645.87.074.04.147.083.22.127.325.196.72.257 1.075.124l1.217-.456a1.125 1.125 0 0 1 1.37.49l1.296 2.247a1.125 1.125 0 0 1-.26 1.431l-1.003.827c-.293.24-.438.613-.43.992a7.723 7.723 0 0 1 0 .255c-.008.378.137.75.43.991l1.004.827c.424.35.534.955.26 1.43l-1.298 2.247a1.125 1.125 0 0 1-1.369.491l-1.217-.456c-.355-.133-.75-.072-1.076.124a6.47 6.47 0 0 1-.22.128c-.331.183-.581.495-.644.869l-.213 1.281c-.09.543-.56.94-1.11.94h-2.594c-.55 0-1.019-.398-1.11-.94l-.213-1.281c-.062-.374-.312-.686-.644-.87a6.52 6.52 0 0 1-.22-.127c-.325-.196-.72-.257-1.076-.124l-1.217.456a1.125 1.125 0 0 1-1.369-.49l-1.297-2.247a1.125 1.125 0 0 1 .26-1.431l1.004-.827c.292-.24.437-.613.43-.991a6.932 6.932 0 0 1 0-.255c.007-.38-.138-.751-.43-.992l-1.004-.827a1.125 1.125 0 0 1-.26-1.43l1.297-2.247a1.125 1.125 0 0 1 1.37-.491l1.216.456c.356.133.751.072 1.076-.124.072-.044.146-.086.22-.128.332-.183.582-.495.644-.869l.214-1.28Z" />
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M15 12a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" />
                            </svg>
                            Panel de Control
                        </a>
                    @endguest
                </div>

                {{-- Mobile Hamburger --}}
                <button
                    onclick="document.getElementById('mobile-menu').classList.toggle('hidden'); document.getElementById('menu-icon').classList.toggle('hidden'); document.getElementById('close-icon').classList.toggle('hidden')"
                    class="lg:hidden p-2 rounded-lg text-gray-500 hover:text-gray-700 hover:bg-gray-100 transition-colors">
                    <svg id="menu-icon" class="size-6" fill="none" viewBox="0 0 24 24" stroke-width="2"
                        stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M3.75 6.75h16.5M3.75 12h16.5m-16.5 5.25h16.5" />
                    </svg>
                    <svg id="close-icon" class="size-6 hidden" fill="none" viewBox="0 0 24 24" stroke-width="2"
                        stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>

        {{-- Mobile Menu --}}
        <div id="mobile-menu" class="lg:hidden border-t border-gray-200 bg-white hidden">
            <div class="px-4 py-4 space-y-2">
                <a href="#inicio"
                    class="block rounded-lg px-3 py-2.5 text-sm font-medium text-gray-700 hover:bg-gray-100 transition-colors">Inicio</a>
                <a href="#oferta-educativa"
                    class="block rounded-lg px-3 py-2.5 text-sm font-medium text-gray-700 hover:bg-gray-100 transition-colors">Oferta
                    Educativa</a>
                <a href="#galeria"
                    class="block rounded-lg px-3 py-2.5 text-sm font-medium text-gray-700 hover:bg-gray-100 transition-colors">Galería</a>
                <a href="#convocatorias"
                    class="block rounded-lg px-3 py-2.5 text-sm font-medium text-gray-700 hover:bg-gray-100 transition-colors">Convocatorias</a>
                <a href="#contacto"
                    class="block rounded-lg px-3 py-2.5 text-sm font-medium text-gray-700 hover:bg-gray-100 transition-colors">Contacto</a>
                <a href="{{ route('blog.index') }}"
                    class="block rounded-lg px-3 py-2.5 text-sm font-medium text-gray-700 hover:bg-gray-100 transition-colors">Blog</a>

                <hr class="my-3 border-gray-200">

                @if ($fichaOpen)
                <button type="button" onclick="Livewire.dispatch('openFichaModal'); document.getElementById('mobile-menu').classList.add('hidden'); document.getElementById('menu-icon').classList.remove('hidden'); document.getElementById('close-icon').classList.add('hidden');"
                    class="flex items-center gap-2 rounded-lg border border-amber-400/60 bg-amber-50 px-4 py-2.5 text-sm font-semibold text-amber-800 hover:bg-amber-100 transition-colors w-full text-left cursor-pointer">
                    <svg class="size-4 shrink-0" fill="none" viewBox="0 0 24 24" stroke-width="2"
                        stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                    </svg>
                    Aspirantes / Pre-registro
                </button>
                @endif

                @guest
                    <a href="{{ url('/alumno/login') }}"
                        class="flex items-center gap-2 rounded-lg bg-gradient-to-r from-green-800 to-blue-900 px-4 py-2.5 text-sm font-semibold text-white transition-all">
                        <svg class="size-4 shrink-0" fill="none" viewBox="0 0 24 24" stroke-width="2"
                            stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M15.75 9V5.25A2.25 2.25 0 0 0 13.5 3h-6a2.25 2.25 0 0 0-2.25 2.25v13.5A2.25 2.25 0 0 0 7.5 21h6a2.25 2.25 0 0 0 2.25-2.25V15m3 0 3-3m0 0-3-3m3 3H9" />
                        </svg>
                        Iniciar Sesión
                    </a>
                @else
                    <a href="{{ url('/alumno') }}"
                        class="flex items-center gap-2 rounded-lg bg-gradient-to-r from-green-800 to-blue-900 px-4 py-2.5 text-sm font-semibold text-white transition-all">
                        <svg class="size-4 shrink-0" fill="none" viewBox="0 0 24 24" stroke-width="2"
                            stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M9.594 3.94c.09-.542.56-.94 1.11-.94h2.593c.55 0 1.02.398 1.11.94l.213 1.281c.063.374.313.686.645.87.074.04.147.083.22.127.325.196.72.257 1.075.124l1.217-.456a1.125 1.125 0 0 1 1.37.49l1.296 2.247a1.125 1.125 0 0 1-.26 1.431l-1.003.827c-.293.24-.438.613-.43.992a7.723 7.723 0 0 1 0 .255c-.008.378.137.75.43.991l1.004.827c.424.35.534.955.26 1.43l-1.298 2.247a1.125 1.125 0 0 1-1.369.491l-1.217-.456c-.355-.133-.75-.072-1.076.124a6.47 6.47 0 0 1-.22.128c-.331.183-.581.495-.644.869l-.213 1.281c-.09.543-.56.94-1.11.94h-2.594c-.55 0-1.019-.398-1.11-.94l-.213-1.281c-.062-.374-.312-.686-.644-.87a6.52 6.52 0 0 1-.22-.127c-.325-.196-.72-.257-1.076-.124l-1.217.456a1.125 1.125 0 0 1-1.369-.49l-1.297-2.247a1.125 1.125 0 0 1 .26-1.431l1.004-.827c.292-.24.437-.613.43-.991a6.932 6.932 0 0 1 0-.255c.007-.38-.138-.751-.43-.992l-1.004-.827a1.125 1.125 0 0 1-.26-1.43l1.297-2.247a1.125 1.125 0 0 1 1.37-.491l1.216.456c.356.133.751.072 1.076-.124.072-.044.146-.086.22-.128.332-.183.582-.495.644-.869l.214-1.28Z" />
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M15 12a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" />
                        </svg>
                        Panel de Control
                    </a>
                @endguest
            </div>
        </div>
    </nav>

    {{-- ============================================================ --}}
    {{--  HERO                                                        --}}
    {{-- ============================================================ --}}
    <section id="inicio" class="relative min-h-[60vh] flex items-center pt-16 lg:pt-20">
        {{-- Background --}}
        <div class="absolute inset-0 z-0">
            <img src="{{ $welcome?->hero_image ? (Str::startsWith($welcome->hero_image, 'http') ? $welcome->hero_image : asset('storage/' . $welcome->hero_image)) : 'https://images.unsplash.com/photo-1588072432836-e10032774350?q=80&w=2072&auto=format&fit=crop' }}"
                alt="CBTA 254" class="size-full object-cover">
            <div class="hero-gradient absolute inset-0"></div>
        </div>

        <div class="relative z-10 w-full max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10 md:py-16">
            <div class="max-w-3xl">
                @if($welcome?->hero_badge)
                    <span
                        class="inline-block rounded-full bg-white/15 backdrop-blur-sm px-3 py-1 text-xs font-semibold text-amber-300 border border-white/20 mb-3">
                        {{ $welcome->hero_badge }}
                    </span>
                @endif

                <h1
                    class="text-2xl sm:text-3xl md:text-4xl lg:text-5xl font-extrabold text-white leading-tight tracking-tight">
                    {!! nl2br(e($welcome?->hero_title ?? 'CBTA No. 254: Por Una Educación, Científica y Crítica')) !!}
                </h1>

                @if($welcome?->hero_description)
                    <p class="mt-3 sm:mt-4 text-sm sm:text-base md:text-lg text-white/80 max-w-2xl leading-relaxed">
                        {{ $welcome->hero_description }}
                    </p>
                @endif

                <div class="mt-6 sm:mt-8 flex flex-wrap gap-4">
                    @if($welcome?->hero_btn_text)
                        <a href="#oferta-educativa"
                            class="inline-flex items-center gap-2 rounded-xl bg-amber-400 px-6 py-3.5 text-sm font-bold text-green-950 shadow-lg shadow-amber-400/30 hover:bg-amber-300 hover:shadow-amber-300/40 transition-all">
                            {{ $welcome->hero_btn_text }}
                            <svg class="size-4" fill="none" viewBox="0 0 24 24" stroke-width="2.5"
                                stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M13.5 4.5 21 12m0 0-7.5 7.5M21 12H3" />
                            </svg>
                        </a>
                    @endif
                    @if($welcome?->hero_btn_secondary_text)
                        <a href="#convocatorias"
                            class="inline-flex items-center gap-2 rounded-xl border border-white/30 px-6 py-3.5 text-sm font-semibold text-white hover:bg-white/10 transition-all">
                            {{ $welcome->hero_btn_secondary_text }}
                        </a>
                    @endif
                </div>
            </div>
        </div>

        {{-- Scroll indicator --}}
        <div class="absolute bottom-4 left-1/2 -translate-x-1/2 z-10 animate-bounce">
            <svg class="size-5 text-white/40" fill="none" viewBox="0 0 24 24" stroke-width="2"
                stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 8.25 12 15.75 4.5 8.25" />
            </svg>
        </div>
    </section>

    @php
        $colorMap = [
            'green' => ['bg' => 'from-green-800/5', 'iconBg' => 'bg-green-100', 'iconBgHover' => 'group-hover:bg-green-800', 'iconColor' => 'text-green-700', 'iconColorHover' => 'group-hover:text-white'],
            'blue' => ['bg' => 'from-blue-800/5', 'iconBg' => 'bg-blue-100', 'iconBgHover' => 'group-hover:bg-blue-900', 'iconColor' => 'text-blue-700', 'iconColorHover' => 'group-hover:text-white'],
            'amber' => ['bg' => 'from-amber-800/5', 'iconBg' => 'bg-amber-100', 'iconBgHover' => 'group-hover:bg-amber-800', 'iconColor' => 'text-amber-700', 'iconColorHover' => 'group-hover:text-white'],
        ];
        $careerIcons = [
            'leaf' => 'M20.25 7.5-.625 10.632a2.25 2.25 0 0 1-2.247 2.118H6.622a2.25 2.25 0 0 1-2.247-2.118L3.75 7.5M10 11.25h4M3.375 7.5h17.25c.621 0 1.125-.504 1.125-1.125v-1.5c0-.621-.504-1.125-1.125-1.125H3.375c-.621 0-1.125.504-1.125 1.125v1.5c0 .621.504 1.125 1.125 1.125Z',
            'computer' => 'M10.5 1.5H8.25A2.25 2.25 0 0 0 6 3.75v16.5a2.25 2.25 0 0 0 2.25 2.25h7.5A2.25 2.25 0 0 0 18 20.25V3.75a2.25 2.25 0 0 0-2.25-2.25H13.5m-3 0V3h3V1.5m-3 0h3m-3 18.75h3',
            'chart' => 'M3.75 3v11.25A2.25 2.25 0 0 0 6 16.5h2.25M3.75 3h-1.5m1.5 0h16.5m0 0h1.5m-1.5 0v11.25A2.25 2.25 0 0 1 18 16.5h-2.25m-7.5 0h7.5m-7.5 0-1 3m8.5-3 1 3m0 0 .5 1.5m-.5-1.5h-9.5m0 0-.5 1.5m.75-9 3-3 2.148 2.148A12.061 12.061 0 0 1 16.5 7.605',
            'sun' => 'M12 21v-8.25M15.75 21v-8.25M8.25 21v-8.25M3 9l9-6 9 6m-1.5 12V10.332A48.36 48.36 0 0 0 12 9.75c-2.551 0-5.056.2-7.5.582V21M3 21h18M12 6.75h.008v.008H12V6.75Z',
        ];
    @endphp

    {{-- ============================================================ --}}
    {{--  OFERTA EDUCATIVA (Carreras)                                  --}}
    {{-- ============================================================ --}}
    <section id="oferta-educativa" class="py-10 sm:py-12 lg:py-16 bg-gray-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center max-w-2xl mx-auto mb-8 sm:mb-10">
                <span
                    class="inline-block rounded-full bg-green-100 px-4 py-1.5 text-xs font-semibold text-green-800 mb-3">
                    Oferta Educativa
                </span>
                <h2 class="text-2xl sm:text-3xl lg:text-4xl font-bold text-gray-900">
                    Carreras T&eacute;cnicas
                </h2>
                <p class="mt-3 text-gray-500 leading-relaxed">
                    Formaci&oacute;n integral con planes de estudio actualizados y pr&aacute;cticas profesionales en el
                    sector.
                </p>
            </div>

            <div class="grid sm:grid-cols-2 lg:grid-cols-4 gap-5 lg:gap-6">
                @foreach($welcome?->careers ?? [] as $career)
                    @php $c = $colorMap[$career['color'] ?? 'green'] ?? $colorMap['green']; @endphp
                    <div
                        class="group relative rounded-2xl bg-white p-5 sm:p-6 shadow-sm hover:shadow-lg transition-all duration-300 hover:-translate-y-1">
                        <div
                            class="absolute inset-0 rounded-2xl bg-gradient-to-b {{ $c['bg'] }} to-transparent opacity-0 group-hover:opacity-100 transition-opacity">
                        </div>
                        <div class="relative">
                            <div
                                class="size-14 rounded-xl {{ $c['iconBg'] }} flex items-center justify-center mb-5 {{ $c['iconBgHover'] }} transition-colors duration-300">
                                <svg class="size-7 {{ $c['iconColor'] }} {{ $c['iconColorHover'] }} transition-colors duration-300"
                                    fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="{{ $careerIcons[$career['icon'] ?? 'leaf'] ?? $careerIcons['leaf'] }}" />
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

    {{-- ============================================================ --}}
    {{--  GALERÍA / INSTALACIONES                                      --}}
    {{-- ============================================================ --}}
    <section id="galeria" class="py-10 sm:py-12 lg:py-16 bg-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center max-w-2xl mx-auto mb-8 sm:mb-10">
                <span
                    class="inline-block rounded-full bg-blue-100 px-4 py-1.5 text-xs font-semibold text-blue-800 mb-3">
                    Conoce el CBTA 254
                </span>
                <h2 class="text-2xl sm:text-3xl lg:text-4xl font-bold text-gray-900">
                    GALERÍA
                </h2>
                <p class="mt-3 text-gray-500 leading-relaxed">
                    Espacios dise&ntilde;ados para el aprendizaje te&oacute;rico y pr&aacute;ctico de nuestros
                    estudiantes.
                </p>
            </div>

            <div class="grid sm:grid-cols-2 lg:grid-cols-3 gap-3 sm:gap-0">
                @foreach($galleryImages as $item)
                    <div class="relative group overflow-hidden  aspect-[4/3] cursor-pointer "
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

    {{-- ============================================================ --}}
    {{--  CONVOCATORIAS / AVISOS                                       --}}
    {{-- ============================================================ --}}
    <section id="convocatorias" class="py-10 sm:py-12 lg:py-16 bg-gray-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center max-w-2xl mx-auto mb-8 sm:mb-10">
                <span
                    class="inline-block rounded-full bg-amber-100 px-4 py-1.5 text-xs font-semibold text-amber-800 mb-3">
                    Convocatorias y Avisos
                </span>
                <h2 class="text-2xl sm:text-3xl lg:text-4xl font-bold text-gray-900">
                    Mantente Informado
                </h2>
                <p class="mt-3 text-gray-500 leading-relaxed">
                    Ent&eacute;rate de las &uacute;ltimas noticias, convocatorias y fechas importantes.
                </p>
            </div>

            <div class="grid sm:grid-cols-2 lg:grid-cols-3 gap-5 lg:gap-6">
                @foreach($communityAnnouncements as $announcement)
                    <div class="rounded-2xl bg-white overflow-hidden shadow-sm hover:shadow-md transition-shadow">
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

    {{-- ============================================================ --}}
    {{--  LOGIN PORTAL (preserva acceso a los 3 paneles existentes)    --}}
    {{-- ============================================================ --}}
    <section id="contacto" class="py-10 sm:py-12 lg:py-16 bg-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center max-w-2xl mx-auto mb-8 sm:mb-10">
                <span
                    class="inline-block rounded-full bg-green-100 px-4 py-1.5 text-xs font-semibold text-green-800 mb-3">
                    Acceso al Sistema
                </span>
                <h2 class="text-2xl sm:text-3xl lg:text-4xl font-bold text-gray-900">
                    Portal de Acceso
                </h2>
                <p class="mt-3 text-gray-500 leading-relaxed">
                    Accede al sistema institucional seg&uacute;n tu perfil.
                </p>
            </div>

            <div class="flex flex-wrap justify-center gap-3 md:gap-5 max-w-3xl mx-auto">
                {{-- Admin --}}
                <a href="{{ url('/admin/login') }}"
                    class="group relative overflow-hidden rounded-xl bg-white border border-gray-200 p-5 text-center transition-all duration-300 hover:-translate-y-1 hover:shadow-lg w-full max-w-56"
                    style="box-shadow: 0 4px 6px rgba(30, 58, 95, 0.08);">
                    <div
                        class="mx-auto size-10 rounded-xl bg-gradient-to-br from-green-800 to-blue-900 flex items-center justify-center text-white mb-2 transition-transform duration-300 group-hover:scale-110">
                        <svg class="size-6" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                            stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M10.5 6h9.75M10.5 6a1.5 1.5 0 1 1-3 0m3 0a1.5 1.5 0 1 0-3 0M3.75 6H7.5m3 12h9.75m-9.75 0a1.5 1.5 0 0 1-3 0m3 0a1.5 1.5 0 0 0-3 0m-3.75 0H7.5m9-6h3.75m-3.75 0a1.5 1.5 0 0 1-3 0m3 0a1.5 1.5 0 0 0-3 0m-9.75 0h9.75" />
                        </svg>
                    </div>
                    <h3 class="text-sm font-bold text-gray-900">Administraci&oacute;n</h3>
                    <p class="mt-0.5 text-[11px] text-gray-400">Personal administrativo</p>
                    <div
                        class="mt-2 inline-flex items-center gap-1 text-xs font-semibold text-green-800 group-hover:gap-2 transition-all">
                        Ingresar
                        <svg class="size-3" fill="none" viewBox="0 0 24 24" stroke-width="2.5"
                            stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M13.5 4.5 21 12m0 0-7.5 7.5M21 12H3" />
                        </svg>
                    </div>
                </a>

                {{-- Profesores --}}
                <a href="{{ url('/profesor/login') }}"
                    class="group relative overflow-hidden rounded-xl bg-white border border-gray-200 p-5 text-center transition-all duration-300 hover:-translate-y-1 hover:shadow-lg w-full max-w-56"
                    style="box-shadow: 0 4px 6px rgba(43, 107, 74, 0.08);">
                    <div
                        class="mx-auto size-10 rounded-xl bg-gradient-to-br from-blue-800 to-blue-600 flex items-center justify-center text-white mb-2 transition-transform duration-300 group-hover:scale-110">
                        <svg class="size-6" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                            stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M4.26 10.147a60.438 60.438 0 0 0-.491 6.347A48.62 48.62 0 0 1 12 20.904a48.62 48.62 0 0 1 8.232-4.41 60.46 60.46 0 0 0-.491-6.347m-15.482 0a50.636 50.636 0 0 0-2.658-.813A59.906 59.906 0 0 1 12 3.493a59.903 59.903 0 0 1 10.399 5.84c-.896.248-1.783.52-2.658.814m-15.482 0A50.717 50.717 0 0 1 12 13.489a50.702 50.702 0 0 1 7.74-3.342M6.75 15a.75.75 0 1 0 0-1.5.75.75 0 0 0 0 1.5Zm0 0v-3.675A55.378 55.378 0 0 1 12 8.443m-7.007 11.55A5.981 5.981 0 0 0 6.75 15.75v-1.5" />
                        </svg>
                    </div>
                    <h3 class="text-sm font-bold text-gray-900">Profesores</h3>
                    <p class="mt-0.5 text-[11px] text-gray-400">Personal docente</p>
                    <div
                        class="mt-2 inline-flex items-center gap-1 text-xs font-semibold text-blue-800 group-hover:gap-2 transition-all">
                        Ingresar
                        <svg class="size-3" fill="none" viewBox="0 0 24 24" stroke-width="2.5"
                            stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M13.5 4.5 21 12m0 0-7.5 7.5M21 12H3" />
                        </svg>
                    </div>
                </a>

                {{-- Alumnos --}}
                <a href="{{ url('/alumno/login') }}"
                    class="group relative overflow-hidden rounded-xl bg-white border border-gray-200 p-5 text-center transition-all duration-300 hover:-translate-y-1 hover:shadow-lg w-full max-w-56"
                    style="box-shadow: 0 4px 6px rgba(139, 111, 71, 0.08);">
                    <div
                        class="mx-auto size-10 rounded-xl bg-gradient-to-br from-amber-700 to-amber-500 flex items-center justify-center text-white mb-2 transition-transform duration-300 group-hover:scale-110">
                        <svg class="size-6" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                            stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M15 19.128a9.38 9.38 0 0 0 2.625.372 9.337 9.337 0 0 0 4.121-.952 4.125 4.125 0 0 0-7.533-2.493M15 19.128v-.003c0-1.113-.285-2.16-.786-3.07M15 19.128v.106A12.318 12.318 0 0 1 8.624 21c-2.331 0-4.512-.645-6.374-1.766l-.001-.109a6.375 6.375 0 0 1 11.964-3.07M12 6.375a3.375 3.375 0 1 1-6.75 0 3.375 3.375 0 0 1 6.75 0Zm8.25 2.25a2.625 2.625 0 1 1-5.25 0 2.625 2.625 0 0 1 5.25 0Z" />
                        </svg>
                    </div>
                    <h3 class="text-sm font-bold text-gray-900">Alumnos</h3>
                    <p class="mt-0.5 text-[11px] text-gray-400">Estudiantes</p>
                    <div
                        class="mt-2 inline-flex items-center gap-1 text-xs font-semibold text-amber-800 group-hover:gap-2 transition-all">
                        Ingresar
                        <svg class="size-3" fill="none" viewBox="0 0 24 24" stroke-width="2.5"
                            stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M13.5 4.5 21 12m0 0-7.5 7.5M21 12H3" />
                        </svg>
                    </div>
                </a>
            </div>
        </div>
    </section>

    {{-- ============================================================ --}}
    {{--  FOOTER                                                        --}}
    {{-- ============================================================ --}}
    <footer class="bg-gray-900 text-gray-300">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10 sm:py-12">
            <div class="grid sm:grid-cols-2 lg:grid-cols-4 gap-8 lg:gap-10">

                {{-- About --}}
                <div class="lg:col-span-1">
                    <div class="flex items-center gap-2.5 mb-3">
                        <div
                            class="size-9 rounded-lg bg-gradient-to-br from-green-700 to-blue-800 flex items-center justify-center text-white font-bold text-xs shadow-sm">
                            CBTA
                        </div>
                        <div>
                            <span class="text-sm font-bold text-white">CBTA 254</span>
                        </div>
                    </div>
                    <p class="text-sm text-gray-400 leading-relaxed">
                        {{ $welcome?->footer_description ?? 'Centro de Bachillerato Tecnológico Agropecuario No. 254. Educación Media Superior de Calidad.' }}
                    </p>
                </div>

                {{-- Links --}}
                <div>
                    <h4 class="text-sm font-bold text-white uppercase tracking-wider mb-3">Instituci&oacute;n</h4>
                    <ul class="space-y-2.5">
                        <li><a href="#"
                                class="text-sm text-gray-400 hover:text-white transition-colors">Misi&oacute;n y
                                Visi&oacute;n</a></li>
                        <li><a href="#"
                                class="text-sm text-gray-400 hover:text-white transition-colors">Directorio</a></li>
                        <li><a href="#"
                                class="text-sm text-gray-400 hover:text-white transition-colors">Transparencia</a></li>
                        <li><a href="#"
                                class="text-sm text-gray-400 hover:text-white transition-colors">Contralor&iacute;a
                                Social</a></li>
                    </ul>
                </div>

                <div>
                    <h4 class="text-sm font-bold text-white uppercase tracking-wider mb-3">Acad&eacute;mico</h4>
                    <ul class="space-y-2.5">
                        <li><a href="#"
                                class="text-sm text-gray-400 hover:text-white transition-colors">Calendario Escolar</a>
                        </li>
                        <li><a href="#"
                                class="text-sm text-gray-400 hover:text-white transition-colors">Horarios</a></li>
                        <li><a href="#"
                                class="text-sm text-gray-400 hover:text-white transition-colors">Reglamento</a></li>
                        <li><a href="#"
                                class="text-sm text-gray-400 hover:text-white transition-colors">Titulaci&oacute;n</a>
                        </li>
                    </ul>
                </div>

                <div>
                    <h4 class="text-sm font-bold text-white uppercase tracking-wider mb-3">Contacto</h4>
                    <ul class="space-y-2.5">
                        <li class="flex items-start gap-2.5 text-sm text-gray-400">
                            <svg class="size-4 shrink-0 mt-0.5 text-amber-400" fill="none" viewBox="0 0 24 24"
                                stroke-width="1.5" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M15 10.5a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" />
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M19.5 10.5c0 7.142-7.5 11.25-7.5 11.25S4.5 17.642 4.5 10.5a7.5 7.5 0 1 1 15 0Z" />
                            </svg>
                            {{ $welcome?->contact_address ?? 'Km. 26.5 Carretera Federal Tecamachalco - Cañada Morelos'}}
                        </li>
                        <li class="flex items-center gap-2.5 text-sm text-gray-400">
                            <svg class="size-4 shrink-0 text-amber-400" fill="none" viewBox="0 0 24 24"
                                stroke-width="1.5" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M21.75 6.75v10.5a2.25 2.25 0 0 1-2.25 2.25h-15a2.25 2.25 0 0 1-2.25-2.25V6.75m19.5 0A2.25 2.25 0 0 0 19.5 4.5h-15a2.25 2.25 0 0 0-2.25 2.25m19.5 0v.243a2.25 2.25 0 0 1-1.07 1.916l-7.5 4.615a2.25 2.25 0 0 1-2.36 0L3.32 8.91a2.25 2.25 0 0 1-1.07-1.916V6.75" />
                            </svg>
                            {{ $welcome?->contact_email ?? 'contacto@cbta254.edu.mx' }}
                        </li>
                        <li class="flex items-center gap-2.5 text-sm text-gray-400">
                            <svg class="size-4 shrink-0 text-amber-400" fill="none" viewBox="0 0 24 24"
                                stroke-width="1.5" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M2.25 6.75c0 8.284 6.716 15 15 15h2.25a2.25 2.25 0 0 0 2.25-2.25v-1.372c0-.516-.351-.966-.852-1.091l-4.423-1.106c-.44-.11-.902.055-1.173.417l-.97 1.293c-.282.376-.769.542-1.21.38a12.035 12.035 0 0 1-7.143-7.143c-.162-.441.004-.928.38-1.21l1.293-.97c.363-.271.527-.734.417-1.173L6.963 3.102a1.125 1.125 0 0 0-1.091-.852H4.5A2.25 2.25 0 0 0 2.25 4.5v2.25Z" />
                            </svg>
                            {{ $welcome?->contact_phone ?? '+52 (249) 154 5238' }}
                        </li>
                    </ul>

                    {{-- Social --}}
                    <div class="flex items-center gap-3 mt-5">
                        <a href="#"
                            class="size-9 rounded-lg bg-gray-800 flex items-center justify-center text-gray-400 hover:bg-amber-500 hover:text-white transition-all"
                            aria-label="Facebook">
                            <svg class="size-4" fill="currentColor" viewBox="0 0 24 24">
                                <path
                                    d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z" />
                            </svg>
                        </a>
                        <a href="#"
                            class="size-9 rounded-lg bg-gray-800 flex items-center justify-center text-gray-400 hover:bg-amber-500 hover:text-white transition-all"
                            aria-label="Twitter">
                            <svg class="size-4" fill="currentColor" viewBox="0 0 24 24">
                                <path
                                    d="M18.244 2.25h3.308l-7.227 8.26 8.502 11.24H16.17l-5.214-6.817L4.99 21.75H1.68l7.73-8.835L1.254 2.25H8.08l4.713 6.231zm-1.161 17.52h1.833L7.084 4.126H5.117z" />
                            </svg>
                        </a>
                        <a href="#"
                            class="size-9 rounded-lg bg-gray-800 flex items-center justify-center text-gray-400 hover:bg-amber-500 hover:text-white transition-all"
                            aria-label="Instagram">
                            <svg class="size-4" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                                stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M16.5 6.75h.008v.008H16.5V6.75Zm-3.75 1.5a3.75 3.75 0 1 0 0 7.5 3.75 3.75 0 0 0 0-7.5ZM21 8.25a5.25 5.25 0 0 0-5.25-5.25h-7.5A5.25 5.25 0 0 0 3 8.25v7.5a5.25 5.25 0 0 0 5.25 5.25h7.5A5.25 5.25 0 0 0 21 15.75v-7.5Z" />
                            </svg>
                        </a>
                        <a href="#"
                            class="size-9 rounded-lg bg-gray-800 flex items-center justify-center text-gray-400 hover:bg-amber-500 hover:text-white transition-all"
                            aria-label="YouTube">
                            <svg class="size-4" fill="currentColor" viewBox="0 0 24 24">
                                <path
                                    d="M23.498 6.186a3.016 3.016 0 0 0-2.122-2.136C19.505 3.545 12 3.545 12 3.545s-7.505 0-9.377.505A3.017 3.017 0 0 0 .502 6.186C0 8.07 0 12 0 12s0 3.93.502 5.814a3.016 3.016 0 0 0 2.122 2.136c1.871.505 9.376.505 9.376.505s7.505 0 9.377-.505a3.015 3.015 0 0 0 2.122-2.136C24 15.93 24 12 24 12s0-3.93-.502-5.814zM9.545 15.568V8.432L15.818 12l-6.273 3.568z" />
                            </svg>
                        </a>
                    </div>
                </div>
            </div>

            <div
                class="mt-6 pt-6 border-t border-gray-800 flex flex-col sm:flex-row items-center justify-between gap-4">
                <p class="text-xs text-gray-500">
                    &copy; {{ date('Y') }} {{ config('app.name', 'CBTA 254') }}. Todos los derechos reservados.
                </p>
                <div class="flex items-center gap-4 text-xs text-gray-500">
                    <a href="#" class="hover:text-white transition-colors">Aviso de Privacidad</a>
                    <a href="#" class="hover:text-white transition-colors">T&eacute;rminos de Uso</a>
                    <span>|</span>
                    <span>CBTA 254 &mdash; DGETAyCM</span>
                </div>
            </div>
        </div>
    </footer>

    {{-- Mobile menu: close on link click --}}
    {{-- Lightbox --}}
    <div id="lightbox" class="fixed inset-0 z-[100] bg-black/90 hidden items-center justify-center p-4"
        onclick="closeLightbox(event)">
        <button onclick="closeLightbox()"
            class="absolute top-4 right-4 size-10 rounded-full bg-white/10 flex items-center justify-center text-white hover:bg-white/20 transition-colors">
            <svg class="size-6" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12" /></svg>
        </button>
        <img id="lightbox-img" src="" alt="" class="max-w-full max-h-[90vh] rounded-2xl shadow-2xl">
        <p id="lightbox-caption" class="absolute bottom-4 left-0 right-0 text-center text-white/80 text-sm"></p>
    </div>

    <script>
        function openLightbox(src, caption) {
            const lb = document.getElementById('lightbox');
            document.getElementById('lightbox-img').src = src;
            document.getElementById('lightbox-caption').textContent = caption || '';
            lb.classList.remove('hidden');
            lb.classList.add('flex');
            document.body.style.overflow = 'hidden';
        }

        function closeLightbox(e) {
            if (e && e.target !== e.currentTarget) return;
            const lb = document.getElementById('lightbox');
            lb.classList.add('hidden');
            lb.classList.remove('flex');
            document.body.style.overflow = '';
        }

        document.addEventListener('keydown', (e) => {
            if (e.key === 'Escape') {
                const lb = document.getElementById('lightbox');
                if (!lb.classList.contains('hidden')) closeLightbox();
            }
        });

        document.getElementById('mobile-menu')?.querySelectorAll('a').forEach(link => {
            link.addEventListener('click', () => {
                document.getElementById('mobile-menu').classList.add('hidden');
                document.getElementById('menu-icon').classList.remove('hidden');
                document.getElementById('close-icon').classList.add('hidden');
            });
        });
    </script>

    @livewire('registro-ficha')

    @livewireScripts
</body>

</html>

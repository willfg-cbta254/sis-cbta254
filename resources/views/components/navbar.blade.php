@props(['active' => ''])

@php
    $fichaOpen = \App\Models\FichaSetting::first()?->is_open ?? false;
@endphp

<nav id="navbar" class="fixed top-0 inset-x-0 z-50 bg-white/95 backdrop-blur border-b border-gray-200">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex items-center justify-between h-16 lg:h-20">

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

            <div class="hidden lg:flex items-center gap-8">
                <a href="{{ $active === 'inicio' ? '#inicio' : url('/#inicio') }}"
                    class="text-sm font-medium {{ $active === 'inicio' ? 'text-green-800 font-semibold' : 'text-gray-600 hover:text-green-800' }} transition-colors">Inicio</a>
                <a href="{{ $active === 'oferta' ? '#oferta-educativa' : url('/#oferta-educativa') }}"
                    class="text-sm font-medium {{ $active === 'oferta' ? 'text-green-800 font-semibold' : 'text-gray-600 hover:text-green-800' }} transition-colors">Oferta
                    Educativa</a>
                <a href="{{ $active === 'galeria' ? '#galeria' : route('blog.gallery') }}"
                    class="text-sm font-medium {{ $active === 'galeria' ? 'text-green-800 font-semibold' : 'text-gray-600 hover:text-green-800' }} transition-colors">Galería</a>
                <a href="{{ $active === 'convocatorias' ? '#convocatorias' : url('/#convocatorias') }}"
                    class="text-sm font-medium {{ $active === 'convocatorias' ? 'text-green-800 font-semibold' : 'text-gray-600 hover:text-green-800' }} transition-colors">Convocatorias</a>
                <a href="{{ $active === 'contacto' ? '#contacto' : url('/#contacto') }}"
                    class="text-sm font-medium {{ $active === 'contacto' ? 'text-green-800 font-semibold' : 'text-gray-600 hover:text-green-800' }} transition-colors">Contacto</a>
                <a href="{{ route('blog.index') }}"
                    class="text-sm font-medium {{ $active === 'blog' ? 'text-green-800 font-semibold' : 'text-gray-600 hover:text-green-800' }} transition-colors">Blog</a>
            </div>

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

    <div id="mobile-menu" class="lg:hidden border-t border-gray-200 bg-white hidden">
        <div class="px-4 py-4 space-y-2">
            <a href="{{ url('/') }}"
                class="block rounded-lg px-3 py-2.5 text-sm font-medium {{ $active === 'inicio' ? 'text-green-800 bg-green-50' : 'text-gray-700 hover:bg-gray-100' }} transition-colors">Inicio</a>
            <a href="{{ url('/#oferta-educativa') }}"
                class="block rounded-lg px-3 py-2.5 text-sm font-medium {{ $active === 'oferta' ? 'text-green-800 bg-green-50' : 'text-gray-700 hover:bg-gray-100' }} transition-colors">Oferta
                Educativa</a>
            <a href="{{ route('blog.gallery') }}"
                class="block rounded-lg px-3 py-2.5 text-sm font-medium {{ $active === 'galeria' ? 'text-green-800 bg-green-50' : 'text-gray-700 hover:bg-gray-100' }} transition-colors">Galería</a>
            <a href="{{ url('/#convocatorias') }}"
                class="block rounded-lg px-3 py-2.5 text-sm font-medium {{ $active === 'convocatorias' ? 'text-green-800 bg-green-50' : 'text-gray-700 hover:bg-gray-100' }} transition-colors">Convocatorias</a>
            <a href="{{ url('/#contacto') }}"
                class="block rounded-lg px-3 py-2.5 text-sm font-medium {{ $active === 'contacto' ? 'text-green-800 bg-green-50' : 'text-gray-700 hover:bg-gray-100' }} transition-colors">Contacto</a>
            <a href="{{ route('blog.index') }}"
                class="block rounded-lg px-3 py-2.5 text-sm font-medium {{ $active === 'blog' ? 'text-green-800 bg-green-50' : 'text-gray-700 hover:bg-gray-100' }} transition-colors">Blog</a>

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

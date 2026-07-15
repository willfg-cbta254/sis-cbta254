<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="Galería del CBTA 254 - Centro de Bachillerato Tecnológico Agropecuario No. 254.">
    <title>Galería | {{ config('app.name', 'CBTA 254') }}</title>
    @fonts
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        html { scroll-behavior: smooth; }
        .gallery-gradient {
            background: linear-gradient(135deg, rgba(13, 79, 59, 0.92) 0%, rgba(30, 58, 95, 0.88) 100%);
        }
    </style>
</head>

<body class="min-h-screen bg-gray-50 text-gray-900 antialiased">

    {{-- NAVBAR --}}
    <nav id="navbar" class="fixed top-0 inset-x-0 z-50 bg-white/95 backdrop-blur border-b border-gray-200">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between h-16 lg:h-20">

                <a href="{{ url('/') }}" class="flex items-center gap-2.5 group">
                    <div class="size-9 rounded-lg bg-gradient-to-br from-green-800 to-blue-900 flex items-center justify-center text-white font-bold text-sm shadow-sm">CBTA</div>
                    <div class="hidden sm:block">
                        <span class="text-sm font-bold text-gray-900 group-hover:text-green-800 transition-colors">CBTA 254</span>
                        <span class="block text-[10px] leading-tight text-gray-500">Educación Media Superior</span>
                    </div>
                </a>

                <div class="hidden lg:flex items-center gap-8">
                    <a href="{{ url('/') }}" class="text-sm font-medium text-gray-600 hover:text-green-800 transition-colors">Inicio</a>
                    <a href="{{ url('/#oferta-educativa') }}" class="text-sm font-medium text-gray-600 hover:text-green-800 transition-colors">Oferta Educativa</a>
                    <a href="{{ route('blog.gallery') }}" class="text-sm font-medium text-green-800 font-semibold transition-colors">Galería</a>
                    <a href="{{ url('/#convocatorias') }}" class="text-sm font-medium text-gray-600 hover:text-green-800 transition-colors">Convocatorias</a>
                    <a href="{{ url('/#contacto') }}" class="text-sm font-medium text-gray-600 hover:text-green-800 transition-colors">Contacto</a>
                    <a href="{{ route('blog.index') }}" class="text-sm font-medium text-gray-600 hover:text-green-800 transition-colors">Blog</a>
                </div>

                <div class="hidden lg:flex items-center gap-3">
                    <a href="#" class="inline-flex items-center gap-1.5 rounded-lg border border-amber-400/60 bg-amber-50 px-4 py-2 text-sm font-semibold text-amber-800 hover:bg-amber-100 transition-colors">
                        <svg class="size-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" /></svg>
                        Aspirantes / Pre-registro
                    </a>
                    @guest
                        <a href="{{ url('/alumnno/login') }}" class="inline-flex items-center gap-1.5 rounded-lg bg-gradient-to-r from-green-800 to-blue-900 px-5 py-2 text-sm font-semibold text-white shadow-sm hover:from-green-700 hover:to-blue-800 transition-all">
                            <svg class="size-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M15.75 9V5.25A2.25 2.25 0 0 0 13.5 3h-6a2.25 2.25 0 0 0-2.25 2.25v13.5A2.25 2.25 0 0 0 7.5 21h6a2.25 2.25 0 0 0 2.25-2.25V15m3 0 3-3m0 0-3-3m3 3H9" /></svg>
                            Iniciar Sesión
                        </a>
                    @else
                        <a href="{{ url('/alumno') }}" class="inline-flex items-center gap-1.5 rounded-lg bg-gradient-to-r from-green-800 to-blue-900 px-5 py-2 text-sm font-semibold text-white shadow-sm hover:from-green-700 hover:to-blue-800 transition-all">
                            <svg class="size-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M9.594 3.94c.09-.542.56-.94 1.11-.94h2.593c.55 0 1.02.398 1.11.94l.213 1.281c.063.374.313.686.645.87.074.04.147.083.22.127.325.196.72.257 1.075.124l1.217-.456a1.125 1.125 0 0 1 1.37.49l1.296 2.247a1.125 1.125 0 0 1-.26 1.431l-1.003.827c-.293.24-.438.613-.43.992a7.723 7.723 0 0 1 0 .255c-.008.378.137.75.43.991l1.004.827c.424.35.534.955.26 1.43l-1.298 2.247a1.125 1.125 0 0 1-1.369.491l-1.217-.456c-.355-.133-.75-.072-1.076.124a6.47 6.47 0 0 1-.22.128c-.331.183-.581.495-.644.869l-.213 1.281c-.09.543-.56.94-1.11.94h-2.594c-.55 0-1.019-.398-1.11-.94l-.213-1.281c-.062-.374-.312-.686-.644-.87a6.52 6.52 0 0 1-.22-.127c-.325-.196-.72-.257-1.076-.124l-1.217.456a1.125 1.125 0 0 1-1.369-.49l-1.297-2.247a1.125 1.125 0 0 1 .26-1.431l1.004-.827c.292-.24.437-.613.43-.991a6.932 6.932 0 0 1 0-.255c.007-.38-.138-.751-.43-.992l-1.004-.827a1.125 1.125 0 0 1-.26-1.43l1.297-2.247a1.125 1.125 0 0 1 1.37-.491l1.216.456c.356.133.751.072 1.076-.124.072-.044.146-.086.22-.128.332-.183.582-.495.644-.869l.214-1.28Z" /><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" /></svg>
                            Panel de Control
                        </a>
                    @endguest
                </div>

                <button onclick="document.getElementById('mobile-menu').classList.toggle('hidden'); document.getElementById('menu-icon').classList.toggle('hidden'); document.getElementById('close-icon').classList.toggle('hidden')" class="lg:hidden p-2 rounded-lg text-gray-500 hover:text-gray-700 hover:bg-gray-100 transition-colors">
                    <svg id="menu-icon" class="size-6" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6.75h16.5M3.75 12h16.5m-16.5 5.25h16.5" /></svg>
                    <svg id="close-icon" class="size-6 hidden" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12" /></svg>
                </button>
            </div>
        </div>

        <div id="mobile-menu" class="lg:hidden border-t border-gray-200 bg-white hidden">
            <div class="px-4 py-4 space-y-2">
                <a href="{{ url('/') }}" class="block rounded-lg px-3 py-2.5 text-sm font-medium text-gray-700 hover:bg-gray-100 transition-colors">Inicio</a>
                <a href="{{ url('/#oferta-educativa') }}" class="block rounded-lg px-3 py-2.5 text-sm font-medium text-gray-700 hover:bg-gray-100 transition-colors">Oferta Educativa</a>
                <a href="{{ route('blog.gallery') }}" class="block rounded-lg px-3 py-2.5 text-sm font-medium text-green-800 bg-green-50 transition-colors">Galería</a>
                <a href="{{ url('/#convocatorias') }}" class="block rounded-lg px-3 py-2.5 text-sm font-medium text-gray-700 hover:bg-gray-100 transition-colors">Convocatorias</a>
                <a href="{{ url('/#contacto') }}" class="block rounded-lg px-3 py-2.5 text-sm font-medium text-gray-700 hover:bg-gray-100 transition-colors">Contacto</a>
                <a href="{{ route('blog.index') }}" class="block rounded-lg px-3 py-2.5 text-sm font-medium text-gray-700 hover:bg-gray-100 transition-colors">Blog</a>
                <hr class="my-2 border-gray-200">
                <a href="#" class="block rounded-lg px-3 py-2.5 text-sm font-medium text-amber-800 bg-amber-50 transition-colors">Aspirantes / Pre-registro</a>
                <a href="{{ url('/alumnno/login') }}" class="block rounded-lg px-3 py-2.5 text-sm font-semibold text-white bg-gradient-to-r from-green-800 to-blue-900 transition-all text-center">Iniciar Sesión</a>
            </div>
        </div>
    </nav>

    {{-- HERO --}}
    <section class="relative pt-16 lg:pt-20">
        <div class="gallery-gradient absolute inset-0"></div>
        <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16 lg:py-24">
            <div class="text-center">
                <h1 class="text-4xl sm:text-5xl lg:text-6xl font-bold text-white mb-4">Galería</h1>
                <p class="text-lg sm:text-xl text-green-100 max-w-2xl mx-auto">Conoce nuestras instalaciones, eventos y actividades</p>
            </div>
        </div>
    </section>

    {{-- CATEGORY FILTER --}}
    @php
        $categories = $images->pluck('category')->unique()->filter();
        $currentCategory = request('category');
    @endphp

    @if($categories->isNotEmpty())
        <section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pt-8">
            <div class="flex flex-wrap gap-2 justify-center">
                <a href="{{ route('blog.gallery') }}"
                    class="rounded-full px-4 py-2 text-sm font-medium transition-colors @unless($currentCategory) bg-green-800 text-white @else bg-gray-200 text-gray-700 hover:bg-gray-300 @endunless">
                    Todas
                </a>
                @foreach($categories as $cat)
                    <a href="{{ route('blog.gallery', ['category' => $cat]) }}"
                        class="rounded-full px-4 py-2 text-sm font-medium transition-colors @if($currentCategory === $cat) bg-green-800 text-white @else bg-gray-200 text-gray-700 hover:bg-gray-300 @endif">
                        {{ match($cat) { 'instalaciones' => 'Instalaciones', 'eventos' => 'Eventos', 'actividades' => 'Actividades', default => ucfirst($cat) } }}
                    </a>
                @endforeach
            </div>
        </section>
    @endif

    {{-- GRID --}}
    <section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10 lg:py-14">
        @if($images->count())
            <div class="columns-1 sm:columns-2 lg:columns-3 xl:columns-4 gap-4 space-y-4">
                @foreach($images as $item)
                    <div class="break-inside-avoid group relative rounded-2xl overflow-hidden bg-white shadow-sm hover:shadow-md transition-shadow cursor-pointer"
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

    {{-- FOOTER --}}
    <footer class="bg-gray-900">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12 lg:py-16">
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-8 lg:gap-12">
                <div>
                    <div class="flex items-center gap-2.5 mb-4">
                        <div class="size-10 rounded-lg bg-gradient-to-br from-green-800 to-blue-900 flex items-center justify-center text-white font-bold text-sm shadow-sm">CBTA</div>
                        <div>
                            <span class="text-sm font-bold text-white">CBTA 254</span>
                            <span class="block text-[10px] leading-tight text-gray-400">Educación Media Superior</span>
                        </div>
                    </div>
                    <p class="text-sm text-gray-400 leading-relaxed">Centro de Bachillerato Tecnológico Agropecuario No. 254. Educación Media Superior de Calidad.</p>
                </div>

                <div>
                    <h4 class="text-sm font-bold text-white uppercase tracking-wider mb-3">Institución</h4>
                    <ul class="space-y-2.5">
                        <li><a href="#" class="text-sm text-gray-400 hover:text-white transition-colors">Misión y Visión</a></li>
                        <li><a href="#" class="text-sm text-gray-400 hover:text-white transition-colors">Directorio</a></li>
                        <li><a href="#" class="text-sm text-gray-400 hover:text-white transition-colors">Transparencia</a></li>
                        <li><a href="#" class="text-sm text-gray-400 hover:text-white transition-colors">Contraloría Social</a></li>
                    </ul>
                </div>

                <div>
                    <h4 class="text-sm font-bold text-white uppercase tracking-wider mb-3">Académico</h4>
                    <ul class="space-y-2.5">
                        <li><a href="#" class="text-sm text-gray-400 hover:text-white transition-colors">Calendario Escolar</a></li>
                        <li><a href="#" class="text-sm text-gray-400 hover:text-white transition-colors">Horarios</a></li>
                        <li><a href="#" class="text-sm text-gray-400 hover:text-white transition-colors">Reglamento</a></li>
                        <li><a href="#" class="text-sm text-gray-400 hover:text-white transition-colors">Titulación</a></li>
                    </ul>
                </div>

                <div>
                    <h4 class="text-sm font-bold text-white uppercase tracking-wider mb-3">Contacto</h4>
                    <ul class="space-y-2.5">
                        <li class="flex items-start gap-2.5 text-sm text-gray-400">
                            <svg class="size-4 shrink-0 mt-0.5 text-amber-400" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M15 10.5a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" /><path stroke-linecap="round" stroke-linejoin="round" d="M19.5 10.5c0 7.142-7.5 11.25-7.5 11.25S4.5 17.642 4.5 10.5a7.5 7.5 0 1 1 15 0Z" /></svg>
                            Carretera Federal México 15, Km. 178, S/N, 63470
                        </li>
                        <li class="flex items-center gap-2.5 text-sm text-gray-400">
                            <svg class="size-4 shrink-0 text-amber-400" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M21.75 6.75v10.5a2.25 2.25 0 0 1-2.25 2.25h-15a2.25 2.25 0 0 1-2.25-2.25V6.75m19.5 0A2.25 2.25 0 0 0 19.5 4.5h-15a2.25 2.25 0 0 0-2.25 2.25m19.5 0v.243a2.25 2.25 0 0 1-1.07 1.916l-7.5 4.615a2.25 2.25 0 0 1-2.36 0L3.32 8.91a2.25 2.25 0 0 1-1.07-1.916V6.75" /></svg>
                            contacto@cbta254.edu.mx
                        </li>
                        <li class="flex items-center gap-2.5 text-sm text-gray-400">
                            <svg class="size-4 shrink-0 text-amber-400" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M2.25 6.75c0 8.284 6.716 15 15 15h2.25a2.25 2.25 0 0 0 2.25-2.25v-1.372c0-.516-.351-.966-.852-1.091l-4.423-1.106c-.44-.11-.902.055-1.173.417l-.97 1.293c-.282.376-.769.542-1.21.38a12.035 12.035 0 0 1-7.143-7.143c-.162-.441.004-.928.38-1.21l1.293-.97c.363-.271.527-.734.417-1.173L6.963 3.102a1.125 1.125 0 0 0-1.091-.852H4.5A2.25 2.25 0 0 0 2.25 4.5v2.25Z" /></svg>
                            +52 (123) 456 7890
                        </li>
                    </ul>

                    <div class="flex items-center gap-3 mt-5">
                        <a href="#" class="size-9 rounded-lg bg-gray-800 flex items-center justify-center text-gray-400 hover:bg-amber-500 hover:text-white transition-all" aria-label="Facebook">
                            <svg class="size-4" fill="currentColor" viewBox="0 0 24 24"><path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z" /></svg>
                        </a>
                        <a href="#" class="size-9 rounded-lg bg-gray-800 flex items-center justify-center text-gray-400 hover:bg-amber-500 hover:text-white transition-all" aria-label="Twitter">
                            <svg class="size-4" fill="currentColor" viewBox="0 0 24 24"><path d="M18.244 2.25h3.308l-7.227 8.26 8.502 11.24H16.17l-5.214-6.817L4.99 21.75H1.68l7.73-8.835L1.254 2.25H8.08l4.713 6.231zm-1.161 17.52h1.833L7.084 4.126H5.117z" /></svg>
                        </a>
                        <a href="#" class="size-9 rounded-lg bg-gray-800 flex items-center justify-center text-gray-400 hover:bg-amber-500 hover:text-white transition-all" aria-label="Instagram">
                            <svg class="size-4" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M16.5 6.75h.008v.008H16.5V6.75Zm-3.75 1.5a3.75 3.75 0 1 0 0 7.5 3.75 3.75 0 0 0 0-7.5ZM21 8.25a5.25 5.25 0 0 0-5.25-5.25h-7.5A5.25 5.25 0 0 0 3 8.25v7.5a5.25 5.25 0 0 0 5.25 5.25h7.5A5.25 5.25 0 0 0 21 15.75v-7.5Z" /></svg>
                        </a>
                        <a href="#" class="size-9 rounded-lg bg-gray-800 flex items-center justify-center text-gray-400 hover:bg-amber-500 hover:text-white transition-all" aria-label="YouTube">
                            <svg class="size-4" fill="currentColor" viewBox="0 0 24 24"><path d="M23.498 6.186a3.016 3.016 0 0 0-2.122-2.136C19.505 3.545 12 3.545 12 3.545s-7.505 0-9.377.505A3.017 3.017 0 0 0 .502 6.186C0 8.07 0 12 0 12s0 3.93.502 5.814a3.016 3.016 0 0 0 2.122 2.136c1.871.505 9.376.505 9.376.505s7.505 0 9.377-.505a3.015 3.015 0 0 0 2.122-2.136C24 15.93 24 12 24 12s0-3.93-.502-5.814zM9.545 15.568V8.432L15.818 12l-6.273 3.568z" /></svg>
                        </a>
                    </div>
                </div>
            </div>

            <div class="mt-6 pt-6 border-t border-gray-800 flex flex-col sm:flex-row items-center justify-between gap-4">
                <p class="text-xs text-gray-500">&copy; {{ date('Y') }} {{ config('app.name', 'CBTA 254') }}. Todos los derechos reservados.</p>
                <div class="flex items-center gap-4 text-xs text-gray-500">
                    <a href="#" class="hover:text-white transition-colors">Aviso de Privacidad</a>
                    <a href="#" class="hover:text-white transition-colors">Términos de Uso</a>
                    <span>|</span>
                    <span>CBTA 254 &mdash; DGETAyCM</span>
                </div>
            </div>
        </div>
    </footer>

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
    </script>

</body>
</html>
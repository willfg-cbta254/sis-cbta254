<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="@yield('meta_description', 'CBTA 254 - Centro de Bachillerato Tecnológico Agropecuario No. 254. Educación Media Superior de Calidad en México.')">
    <title>@yield('title', config('app.name', 'CBTA 254') . ' | Educación Media Superior')</title>
    @fonts
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        html { scroll-behavior: smooth; }
        .hero-gradient {
            background: linear-gradient(135deg, rgba(13, 79, 41, 0.92) 0%, rgba(30, 58, 95, 0.88) 80%);
        }
        .section-gradient {
            background: linear-gradient(135deg, rgba(13, 79, 59, 0.92) 0%, rgba(30, 58, 95, 0.88) 100%);
        }
        [x-cloak] { display: none !important; }
    </style>
    @stack('styles')
    @livewireStyles
</head>

<body class="min-h-screen bg-white text-gray-900 antialiased">

    <x-navbar :active="$navbarActive ?? ''" />

    @yield('content')

    <x-footer />

    {{-- Aviso Modal --}}
    <div id="aviso-modal" class="fixed inset-0 z-[100] bg-black/70 hidden items-center justify-center p-4"
        onclick="closeAvisoModal(event)">
        <div class="relative bg-white rounded-2xl max-w-2xl w-full max-h-[85vh] overflow-y-auto shadow-2xl"
            onclick="event.stopPropagation()">
            <div class="h-1.5 bg-gradient-to-r from-green-700 to-green-500 rounded-t-2xl"></div>
            <button onclick="closeAvisoModal()"
                class="absolute top-4 right-4 size-8 rounded-full bg-gray-100 flex items-center justify-center text-gray-500 hover:bg-gray-200 transition-colors">
                <svg class="size-5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12" /></svg>
            </button>
            <div class="p-6 sm:p-8">
                <span class="inline-block rounded-full bg-green-100 text-green-700 px-3 py-1 text-xs font-semibold mb-3">Comunidad</span>
                <h3 id="aviso-modal-title" class="text-xl font-bold text-gray-900 mb-3"></h3>
                <div id="aviso-modal-content" class="text-sm text-gray-700 leading-relaxed [&_p]:mb-3 [&_ul]:list-disc [&_ul]:pl-5 [&_ol]:list-decimal [&_ol]:pl-5 [&_h1]:text-lg [&_h1]:font-bold [&_h2]:text-base [&_h2]:font-semibold [&_h3]:text-sm [&_h3]:font-semibold"></div>
                <time id="aviso-modal-date" class="block mt-4 pt-4 border-t border-gray-100 text-xs text-gray-400"></time>
            </div>
        </div>
    </div>

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
        function openAvisoModal(el) {
            const title = el.dataset.title;
            const contentBase64 = el.dataset.content;
            const date = el.dataset.date;
            document.getElementById('aviso-modal-title').textContent = title;
            document.getElementById('aviso-modal-content').innerHTML = atob(contentBase64);
            document.getElementById('aviso-modal-date').textContent = 'Publicado: ' + date;
            document.getElementById('aviso-modal').classList.remove('hidden');
            document.getElementById('aviso-modal').classList.add('flex');
            document.body.style.overflow = 'hidden';
        }

        function closeAvisoModal(e) {
            if (e && e.target !== e.currentTarget) return;
            document.getElementById('aviso-modal').classList.add('hidden');
            document.getElementById('aviso-modal').classList.remove('flex');
            document.body.style.overflow = '';
        }

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
                const avisoModal = document.getElementById('aviso-modal');
                if (!avisoModal.classList.contains('hidden')) closeAvisoModal();
                const lb = document.getElementById('lightbox');
                if (!lb.classList.contains('hidden')) closeLightbox();
            }
        });

        document.getElementById('mobile-menu')?.querySelectorAll('a, button').forEach(el => {
            el.addEventListener('click', () => {
                document.getElementById('mobile-menu').classList.add('hidden');
                document.getElementById('menu-icon').classList.remove('hidden');
                document.getElementById('close-icon').classList.add('hidden');
            });
        });
    </script>

    @stack('scripts')

    @livewireScripts
</body>

</html>

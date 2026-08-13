@php
    $welcome = \App\Models\WelcomeContent::first();
@endphp

<footer class="bg-gray-900 text-gray-300">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10 sm:py-12">
        <div class="grid sm:grid-cols-2 lg:grid-cols-4 gap-8 lg:gap-10">

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

            <div>
                <h4 class="text-sm font-bold text-white uppercase tracking-wider mb-3">Institución</h4>
                <ul class="space-y-2.5">
                    <li><a href="{{ url('/#filosofia') }}"
                            class="text-sm text-gray-400 hover:text-white transition-colors">Misión y Visión</a></li>
                    <li><a href="#"
                            class="text-sm text-gray-400 hover:text-white transition-colors">Directorio</a></li>
                    <li><a href="#"
                            class="text-sm text-gray-400 hover:text-white transition-colors">Transparencia</a></li>
                    <li><a href="#"
                            class="text-sm text-gray-400 hover:text-white transition-colors">Contraloría Social</a></li>
                </ul>
            </div>

            <div>
                <h4 class="text-sm font-bold text-white uppercase tracking-wider mb-3">Académico</h4>
                <ul class="space-y-2.5">
                    <li><a href="#"
                            class="text-sm text-gray-400 hover:text-white transition-colors">Calendario Escolar</a></li>
                    <li><a href="#"
                            class="text-sm text-gray-400 hover:text-white transition-colors">Horarios</a></li>
                    <li><a href="#"
                            class="text-sm text-gray-400 hover:text-white transition-colors">Reglamento</a></li>
                    <li><a href="#"
                            class="text-sm text-gray-400 hover:text-white transition-colors">Titulación</a></li>
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
                <a href="#" class="hover:text-white transition-colors">Términos de Uso</a>
                <span>|</span>
                <span>CBTA 254 &mdash; DGETAyCM</span>
            </div>
        </div>
    </div>
</footer>

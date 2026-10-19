@php
    $panels = [
        'admin' => [
            'image' => 'images/admin.jpg',
            'badge' => 'Panel Administrativo',
            'title' => 'Gestión escolar',
            'subtitle' => 'Acceso exclusivo para personal autorizado.',
        ],
        'alumno' => [
            'image' => 'images/Alumnos.jpg',
            'badge' => 'Panel Alumnos',
            'title' => 'Tu vida escolar',
            'subtitle' => 'Consulta calificaciones, horarios, asistencias y avisos.',
        ],
        'profesor' => [
            'image' => 'images/Teacher.jpg',
            'badge' => 'Panel Docentes',
            'title' => 'Gestión académica',
            'subtitle' => 'Registra asistencias, calificaciones y consulta tu carga docente.',
        ],
        'financiero' => [
            'image' => 'images/Financieros.jpg',
            'badge' => 'Panel Financiero',
            'title' => 'Control financiero',
            'subtitle' => 'Consulta pagos, cortes de caja y estados de cuenta.',
        ],
        'servicios-escolares' => [
            'image' => 'images/ControlEscolar.jpg',
            'badge' => 'Panel Servicios Escolares',
            'title' => 'Control escolar',
            'subtitle' => 'Administra inscripciones, grupos y carga académica.',
        ],
    ];

    $panel = $panels[filament()->getCurrentPanel()?->getId()] ?? $panels['admin'];
@endphp

<div class="relative flex h-full w-full flex-col justify-between overflow-hidden bg-slate-900">
    {{-- Background image con overlay --}}
    <div class="absolute inset-0">
        <img src="{{ asset($panel['image']) }}" alt="CBTA 254" class="h-full w-full object-cover" />
        <div class="absolute inset-0 bg-gradient-to-br from-emerald-900/90 via-[#004225]/85 to-slate-900/90"></div>
        <div class="absolute inset-0 bg-[radial-gradient(circle_at_20%_80%,rgba(16,185,129,0.25),transparent_50%)]"></div>
    </div>

    {{-- Contenido --}}
    <div class="relative flex flex-1 flex-col justify-center px-8 py-10 lg:px-12 xl:px-16">
        <div class="mb-10 flex items-center gap-3">
            <img src="{{ asset('images/logo.png') }}" alt="CBTA 254" class="h-12 w-12 rounded-xl bg-white p-1.5 shadow-lg object-contain" />
            <div class="text-white">
                <p class="text-sm font-bold tracking-widest">CBTA 254</p>
                <p class="text-xs text-emerald-100">“Cuacnopalan” · Palmar de Bravo, Pue.</p>
            </div>
        </div>

        <div class="max-w-lg">
            <div class="inline-flex items-center gap-2 rounded-full bg-white/10 px-3 py-1 text-xs font-medium text-emerald-100 ring-1 ring-white/20 backdrop-blur">
                <span class="size-2 rounded-full bg-emerald-400 animate-pulse"></span>
                {{ $panel['badge'] }}
            </div>
            <h1 class="mt-4 text-3xl font-extrabold leading-tight tracking-tight text-white xl:text-4xl">
                {{ $panel['title'] }}<br />
            </h1>
            <p class="mt-3 text-sm leading-relaxed text-emerald-50/90">
                {{ $panel['subtitle'] }}
            </p>

            <div class="mt-8 grid grid-cols-3 gap-3">
                <div class="rounded-2xl bg-white/10 p-4 ring-1 ring-white/15 backdrop-blur">
                    <p class="text-2xl font-bold text-white">100%</p>
                    <p class="text-xs text-emerald-100">Digital</p>
                </div>
                <div class="rounded-2xl bg-white/10 p-4 ring-1 ring-white/15 backdrop-blur">
                    <p class="text-2xl font-bold text-white">24/7</p>
                    <p class="text-xs text-emerald-100">Disponible</p>
                </div>
                <div class="rounded-2xl bg-white/10 p-4 ring-1 ring-white/15 backdrop-blur">
                    <p class="text-2xl font-bold text-white">SEP</p>
                    <p class="text-xs text-emerald-100">DGETAyCM</p>
                </div>
            </div>
        </div>
    </div>

    <div class="relative border-t border-white/10 bg-black/20 px-8 py-4 backdrop-blur">
        <p class="text-xs text-white/60">
            Centro de Bachillerato Tecnológico Agropecuario No. 254 · Sistema de Control Escolar
        </p>
    </div>
</div>

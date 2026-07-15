{{--
    ============================================================
    503.blade.php - Página de Mantenimiento (Service Unavailable)
    ============================================================

    Vista personalizada para el código de estado HTTP 503.
    Se muestra automáticamente cuando la aplicación está en
    modo mantenimiento (php artisan down).

    Características:
    - Diseño moderno y responsivo con Tailwind CSS
    - Animaciones CSS (engranajes giratorios, fade-in, dots pulsantes)
    - Fondo con patrón de cuadrícula sutil
    - Efectos de desenfoque y burbujas decorativas
    - Títulos y textos informativos para el usuario
--}}

<!DOCTYPE html>
<html lang="es">
<head>
    {{-- Meta tags básicos para codificación y viewport responsivo --}}
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    {{--
        Título dinámico que usa el nombre de la aplicación
        definido en config/app.php
    --}}
    <title>Mantenimiento - {{ config('app.name') }}</title>

    {{-- Tailwind CSS vía CDN para estilos utilitarios sin build step --}}
    <script src="https://cdn.tailwindcss.com"></script>

    <style>
        {{-- Fuente Inter de Google Fonts para tipografía moderna y limpia --}}
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap');

        {{-- Asigna Inter como fuente global para todo el documento --}}
        * { font-family: 'Inter', sans-serif; }

        {{--
            ====================
            ANIMACIONES
            ====================

            .gear         -> Rotación continua horaria (4s)
            .gear-reverse -> Rotación continua antihoraria (5s)
            Representan engranajes en movimiento para dar sensación
            de actividad durante el mantenimiento.
        --}}
        .gear {
            animation: spin 4s linear infinite;
        }
        .gear-reverse {
            animation: spin-reverse 5s linear infinite;
        }

        @keyframes spin {
            from { transform: rotate(0deg); }
            to   { transform: rotate(360deg); }
        }

        @keyframes spin-reverse {
            from { transform: rotate(360deg); }
            to   { transform: rotate(0deg); }
        }

        {{--
            .fade-in -> Animación de entrada con desplazamiento vertical.
            Los elementos comienzan invisibles (opacity: 0) y 30px abajo,
            y terminan visibles en su posición final.

            .fade-in-delay-{1..4} -> Retrasos escalonados para que
            los elementos aparezcan secuencialmente (efecto cascada).
        --}}
        .fade-in {
            animation: fadeInUp 0.8s ease-out forwards;
            opacity: 0;
        }
        .fade-in-delay-1 { animation-delay: 0.2s; }
        .fade-in-delay-2 { animation-delay: 0.4s; }
        .fade-in-delay-3 { animation-delay: 0.6s; }
        .fade-in-delay-4 { animation-delay: 0.8s; }

        @keyframes fadeInUp {
            from { opacity: 0; transform: translateY(30px); }
            to   { opacity: 1; transform: translateY(0); }
        }

        {{--
            .bg-grid -> Patrón de cuadrícula como fondo decorativo.
            Crea líneas horizontales y verticales tenues usando
            linear-gradient con 60px de espaciado.
        --}}
        .bg-grid {
            background-image:
                linear-gradient(rgba(59, 130, 246, 0.05) 1px, transparent 1px),
                linear-gradient(90deg, rgba(59, 130, 246, 0.05) 1px, transparent 1px);
            background-size: 60px 60px;
        }

        {{--
            .dot-pulse -> Indicador de carga con 3 puntos.
            Cada punto escala y cambia opacidad rítmicamente
            con retrasos escalonados para efecto de onda.
        --}}
        .dot-pulse {
            display: inline-flex;
            gap: 6px;
        }
        .dot-pulse span {
            width: 8px;
            height: 8px;
            border-radius: 50%;
            background: #3b82f6;
            animation: pulse 1.4s ease-in-out infinite;
        }
        .dot-pulse span:nth-child(2) { animation-delay: 0.2s; }
        .dot-pulse span:nth-child(3) { animation-delay: 0.4s; }

        @keyframes pulse {
            0%, 80%, 100% { opacity: 0.2; transform: scale(0.8); }
            40%            { opacity: 1;   transform: scale(1.2); }
        }

        {{--
            .glow -> Sombra exterior con tono azul para dar un
            efecto de brillo sutil alrededor de la tarjeta principal.
        --}}
        .glow {
            box-shadow: 0 0 40px rgba(59, 130, 246, 0.15),
                        0 0 80px rgba(59, 130, 246, 0.05);
        }
    </style>
</head>

{{--
    Body:
    - Fondo con gradiente diagonal (slate -> blue -> indigo)
    - Clases flex para centrado vertical y horizontal
    - bg-grid para el patrón de fondo
--}}
<body class="min-h-screen bg-gradient-to-br from-slate-50 via-blue-50 to-indigo-100 flex items-center justify-center p-4 bg-grid">

    {{-- Contenedor principal con ancho máximo de 2xl --}}
    <div class="relative w-full max-w-2xl">

        {{--
            Elementos decorativos de fondo:
            Dos burbujas grandes con desenfoque (blur-3xl) y opacidad
            baja, animadas con pulse para que respiren lentamente.
            Una arriba-izquierda (azul) y otra abajo-derecha (índigo).
        --}}
        <div class="absolute -top-20 -left-20 w-72 h-72 bg-blue-200 rounded-full mix-blend-multiply filter blur-3xl opacity-20 animate-pulse"></div>
        <div class="absolute -bottom-20 -right-20 w-72 h-72 bg-indigo-200 rounded-full mix-blend-multiply filter blur-3xl opacity-20 animate-pulse" style="animation-delay: 2s;"></div>

        {{--
            Tarjeta principal:
            - Fondo blanco semitransparente con desenfoque (backdrop-blur)
            - Esquinas redondeadas (rounded-3xl)
            - Sombra y efecto glow
            - Borde sutil blanco semitransparente
        --}}
        <div class="relative bg-white/80 backdrop-blur-xl rounded-3xl shadow-2xl glow p-12 text-center border border-white/50">

            {{--
                Engranajes decorativos animados:
                Tres SVG con rotación continua (gear y gear-reverse)
                para dar sensación de "trabajo en progreso".
                Tamaños variados para un efecto más orgánico.
            --}}
            <div class="flex justify-center items-center gap-4 mb-8 fade-in">
                {{-- Engranaje pequeño (horario) --}}
                <svg class="gear w-16 h-16 text-blue-500/80" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                    <path d="M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 110 4m-6 8a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4m6 6v10m6-2a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4" stroke-linecap="round"/>
                </svg>

                {{-- Engranaje grande (antihorario) para contraste visual --}}
                <svg class="gear-reverse w-20 h-20 text-indigo-500/60" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                    <path d="M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 110 4m-6 8a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4m6 6v10m6-2a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4" stroke-linecap="round"/>
                </svg>

                {{-- Engranaje pequeño (horario) --}}
                <svg class="gear w-12 h-12 text-blue-400/70" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                    <path d="M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 110 4m-6 8a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4m6 6v10m6-2a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4" stroke-linecap="round"/>
                </svg>
            </div>

            {{--
                Código de estado HTTP (503):
                Texto degradado con gradient-to-r para impacto visual.
                Usa bg-clip-text para aplicar el gradiente solo al texto.
            --}}
            <div class="fade-in fade-in-delay-1">
                <h1 class="text-8xl font-extrabold text-transparent bg-clip-text bg-gradient-to-r from-blue-600 via-indigo-600 to-purple-600 tracking-tight">
                    503
                </h1>
            </div>

            {{--
                Título y descripción:
                Mensaje amigable informando al usuario que el sitio
                está en mantenimiento programado.
            --}}
            <div class="fade-in fade-in-delay-2 mt-4">
                <h2 class="text-3xl font-bold text-slate-800">
                    Sitio en mantenimiento
                </h2>
                <p class="text-lg text-slate-500 mt-3 leading-relaxed max-w-md mx-auto">
                    Estamos realizando mejoras para brindarte un mejor servicio.
                    Volveremos en breve.
                </p>
            </div>

            {{--
                Indicador de carga (dots animados):
                Tres puntos que pulsean secuencialmente para transmitir
                que el sistema sigue activo.
            --}}
            <div class="fade-in fade-in-delay-3 mt-8 flex justify-center">
                <div class="dot-pulse">
                    <span></span>
                    <span></span>
                    <span></span>
                </div>
            </div>

            {{--
                Divisor decorativo:
                Línea con gradiente + punto central para separar
                visualmente el contenido principal del footer.
            --}}
            <div class="fade-in fade-in-delay-3 mt-8 flex items-center justify-center gap-4">
                <div class="h-px w-16 bg-gradient-to-r from-transparent via-blue-300 to-transparent"></div>
                <div class="h-2 w-2 rounded-full bg-blue-400"></div>
                <div class="h-px w-16 bg-gradient-to-r from-transparent via-blue-300 to-transparent"></div>
            </div>

            {{--
                Footer informativo:
                - Copyright dinámico con año actual y nombre de la app
                - Mensaje de reaseguramiento para el usuario
            --}}
            <div class="fade-in fade-in-delay-4 mt-8">
                <p class="text-sm text-slate-400">
                    &copy; {{ date('Y') }} {{ config('app.name') }}. Todos los derechos reservados.
                </p>
                <p class="text-xs text-slate-300 mt-1">
                    Estaremos de vuelta en unos minutos.
                </p>
            </div>

        </div>
        {{-- Fin tarjeta principal --}}
    </div>
    {{-- Fin contenedor --}}
</body>
</html>

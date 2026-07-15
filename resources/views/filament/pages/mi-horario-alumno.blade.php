<x-filament-panels::page>
    @php $user = auth()->user(); @endphp

    <div class="mb-6 flex items-center gap-4 rounded-xl border border-gray-200 bg-white p-4 shadow-sm dark:border-gray-700 dark:bg-gray-800">
        <div class="flex-shrink-0">
            <img src="{{ $user->getFilamentAvatarUrl() ?: 'https://ui-avatars.com/api/?name='.urlencode($user->name).'&color=004225&background=e6f7ef' }}"
                alt="{{ $user->name }}"
                class="size-12 rounded-full object-cover ring-2 ring-primary-500">
        </div>
        <div>
            <h1 class="text-xl font-bold text-gray-900 dark:text-white">{{ $user->name }}</h1>
            <p class="text-sm text-gray-500 dark:text-gray-400">
                {{ $user->alumno?->matricula ? 'Matrícula: '.$user->alumno->matricula : 'Alumno' }}
                @if($user->alumno?->carrera)
                    &middot; {{ $user->alumno->carrera->nombre }}
                @endif
            </p>
        </div>
    </div>

    <div class="space-y-6">
        @if($horarioPorDia->isEmpty())
            <div class="flex flex-col items-center justify-center py-12 text-gray-400">
                <x-heroicon-o-clock class="size-16 mb-4" />
                <p class="text-lg font-medium">No hay horario disponible</p>
                <p class="text-sm">Aún no se ha publicado tu horario de clases.</p>
            </div>
        @else
            <div class="grid grid-cols-1 gap-4 lg:grid-cols-5">
                @foreach($dias as $dia)
                    <div class="rounded-xl border border-gray-200 bg-white shadow-sm dark:border-gray-700 dark:bg-gray-800">
                        <div class="border-b border-gray-200 px-4 py-3 dark:border-gray-700">
                            <h3 class="text-center text-lg font-bold text-gray-800 dark:text-gray-200">{{ $dia }}</h3>
                        </div>
                        <div class="space-y-2 p-3">
                            @php $clases = $horarioPorDia->get($dia, collect()); @endphp
                            @forelse($clases as $horario)
                                <div class="rounded-lg border border-primary-200 bg-primary-50 p-3 dark:border-primary-800 dark:bg-primary-900/20">
                                    <div class="mb-1 flex items-center justify-between">
                                        <span class="text-xs font-semibold text-primary-600 dark:text-primary-400">
                                            {{ \Carbon\Carbon::parse($horario->hora_inicio)->format('H:i') }} - {{ \Carbon\Carbon::parse($horario->hora_fin)->format('H:i') }}
                                        </span>
                                        @if($horario->aula)
                                            <span class="text-xs text-gray-500 dark:text-gray-400">
                                                <x-heroicon-m-map-pin class="inline size-3" /> {{ $horario->aula }}
                                            </span>
                                        @endif
                                    </div>
                                    <p class="text-sm font-semibold text-gray-800 dark:text-gray-200">
                                        {{ $horario->cargaAcademica->materia->nombre }}
                                    </p>
                                    <p class="text-xs text-gray-500 dark:text-gray-400">
                                        Profesor: <span class="font-medium">{{ $horario->cargaAcademica->profesor->user->name }}</span>
                                    </p>
                                </div>
                            @empty
                                <p class="py-4 text-center text-sm text-gray-400">Sin clases</p>
                            @endforelse
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    </div>
</x-filament-panels::page>

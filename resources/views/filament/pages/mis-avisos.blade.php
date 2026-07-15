<x-filament-panels::page>
    <div class="space-y-6">
        @forelse($avisos as $aviso)
            @php
                $config = match($aviso->type) {
                    'community' => [
                        'bar' => 'from-green-600 to-emerald-400',
                        'badge_bg' => 'bg-green-100',
                        'badge_text' => 'text-green-700',
                        'icon' => '<svg class="size-4" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M8.625 12a.375.375 0 1 1-.75 0 .375.375 0 0 1 .75 0Zm0 0H8.25m4.125 0a.375.375 0 1 1-.75 0 .375.375 0 0 1 .75 0Zm0 0H12m4.125 0a.375.375 0 1 1-.75 0 .375.375 0 0 1 .75 0Zm0 0h-.375M21 12c0 4.556-4.03 8.25-9 8.25a9.764 9.764 0 0 1-2.555-.337A5.972 5.972 0 0 1 5.41 20.97a5.969 5.969 0 0 1-.474-.065 4.48 4.48 0 0 0 .978-2.025c.09-.457-.133-.901-.467-1.226C3.93 16.178 3 14.189 3 12c0-4.556 4.03-8.25 9-8.25s9 3.694 9 8.25Z" /></svg>',
                    ],
                    'group' => [
                        'bar' => 'from-amber-500 to-yellow-400',
                        'badge_bg' => 'bg-amber-100',
                        'badge_text' => 'text-amber-700',
                        'icon' => '<svg class="size-4" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M15 19.128a9.38 9.38 0 0 0 2.625.372 9.337 9.337 0 0 0 4.121-.952 4.125 4.125 0 0 0-7.533-2.493M15 19.128v-.003c0-1.113-.285-2.16-.786-3.07M15 19.128v.106A12.318 12.318 0 0 1 8.624 21c-2.331 0-4.512-.645-6.374-1.766l-.001-.109a6.375 6.375 0 0 1 11.964-3.07M12 6.375a3.375 3.375 0 1 1-6.75 0 3.375 3.375 0 0 1 6.75 0Zm8.25 2.25a2.625 2.625 0 1 1-5.25 0 2.625 2.625 0 0 1 5.25 0Z" /></svg>',
                    ],
                    'student' => [
                        'bar' => 'from-blue-600 to-indigo-400',
                        'badge_bg' => 'bg-blue-100',
                        'badge_text' => 'text-blue-700',
                        'icon' => '<svg class="size-4" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 1 1-7.5 0 3.75 3.75 0 0 1 7.5 0ZM4.501 20.118a7.5 7.5 0 0 1 14.998 0A17.933 17.933 0 0 1 12 21.75c-2.676 0-5.216-.584-7.499-1.632Z" /></svg>',
                    ],
                };
            @endphp

            <div class="group rounded-2xl bg-white border border-gray-200 shadow-sm hover:shadow-lg transition-all duration-300 overflow-hidden">
                <div class="h-2 bg-gradient-to-r {{ $config['bar'] }}"></div>
                <div class="p-6">
                    <div class="flex items-center gap-3 mb-3">
                        <span class="inline-flex items-center gap-1.5 rounded-full {{ $config['badge_bg'] }} {{ $config['badge_text'] }} px-3 py-1 text-xs font-semibold">
                            {!! $config['icon'] !!}
                            {{ match($aviso->type) { 'community' => 'Comunidad', 'group' => 'Grupo', 'student' => 'Personal', default => $aviso->type } }}
                        </span>
                        @if($aviso->published_at)
                            <span class="text-xs text-gray-400 flex items-center gap-1">
                                <svg class="size-3.5" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" /></svg>
                                {{ $aviso->published_at->isoFormat('D MMM, YYYY') }}
                            </span>
                        @endif
                    </div>

                    <h3 class="text-lg font-bold text-gray-900 group-hover:text-green-800 transition-colors">{{ $aviso->title }}</h3>

                    <div class="mt-3 text-sm text-gray-600 leading-relaxed prose prose-sm max-w-none prose-p:mb-2 prose-p:last:mb-0 prose-ul:my-1 prose-li:my-0.5">
                        {!! $aviso->content !!}
                    </div>
                </div>
            </div>
        @empty
            <div class="text-center py-20">
                <svg class="size-20 mx-auto text-gray-200 mb-6" fill="none" viewBox="0 0 24 24" stroke-width="0.8" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M10.34 15.84c-.688-.06-1.386-.09-2.09-.09H7.5a4.5 4.5 0 1 1 0-9h.75c.704 0 1.402-.03 2.09-.09m0 9.18c.253.962.584 1.892.985 2.783.247.55.06 1.21-.463 1.511l-.657.38a.484.484 0 0 1-.648-.248 16.456 16.456 0 0 1-1.357-4.307m2.09-9.18a16.456 16.456 0 0 1 1.357-4.307.484.484 0 0 1 .648-.248l.657.38c.523.3.71.96.463 1.511a16.521 16.521 0 0 0-.985 2.783m0 0a18.97 18.97 0 0 1-.09 2.927m0 0a16.387 16.387 0 0 0 3.244 1.742 16.387 16.387 0 0 0 3.244-1.742" />
                </svg>
                <h2 class="text-xl font-bold text-gray-400 mb-2">No hay avisos</h2>
                <p class="text-sm text-gray-400">No tienes avisos nuevos por el momento.</p>
            </div>
        @endforelse
    </div>
</x-filament-panels::page>
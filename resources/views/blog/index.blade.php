@php
    $welcome = \App\Models\WelcomeContent::first();
@endphp

@extends('layouts.app', ['navbarActive' => 'blog'])

@section('title', 'Blog | ' . config('app.name', 'CBTA 254'))

@section('meta_description', 'Blog del CBTA 254 - Centro de Bachillerato Tecnológico Agropecuario No. 254.')

@section('content')
    {{-- HERO --}}
    <section class="relative min-h-[60vh] flex items-center pt-16 lg:pt-20">
        <div class="absolute inset-0 z-0">
            <img src="{{ $welcome?->hero_image ? (Str::startsWith($welcome->hero_image, 'http') ? $welcome->hero_image : asset('storage/' . $welcome->hero_image)) : 'https://images.unsplash.com/photo-1588072432836-e10032774350?q=80&w=2072&auto=format&fit=crop' }}"
                alt="" class="size-full object-cover">
            <div class="section-gradient absolute inset-0"></div>
        </div>
        <div class="relative z-10 w-full max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10 md:py-16">
            <div class="text-center">
                <h1 class="text-4xl sm:text-5xl lg:text-6xl font-bold text-white mb-4">Blog</h1>
                <p class="text-lg sm:text-xl text-green-100 max-w-2xl mx-auto">Noticias, artículos y novedades del CBTA 254</p>
            </div>
        </div>
    </section>

    {{-- POSTS GRID --}}
    <section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12 lg:py-16">
        @if($posts->count())
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6 lg:gap-8">
                @foreach($posts as $post)
                    <article class="bg-white rounded-2xl shadow-sm border border-gray-200 overflow-hidden hover:shadow-md transition-shadow flex flex-col">
                        @if($post->featured_image)
                            <img src="{{ asset('storage/' . $post->featured_image) }}" alt="{{ $post->title }}" class="w-full h-48 object-cover">
                        @else
                            <div class="w-full h-48 bg-gradient-to-br from-green-800 to-blue-900 flex items-center justify-center">
                                <svg class="size-12 text-white/40" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M12 7.5h1.5m-1.5 3h1.5m-7.5 3h7.5m-7.5 3h7.5m3-9h3.375c.621 0 1.125.504 1.125 1.125V18a2.25 2.25 0 0 1-2.25 2.25M16.5 7.5V18a2.25 2.25 0 0 0 2.25 2.25M16.5 7.5V4.875c0-.621-.504-1.125-1.125-1.125H4.125C3.504 3.75 3 4.254 3 4.875V18a2.25 2.25 0 0 0 2.25 2.25h13.5M6 7.5h3v3H6v-3Z" /></svg>
                            </div>
                        @endif

                        <div class="p-5 flex-1 flex flex-col">
                            <div class="flex items-center gap-3 text-xs text-gray-500 mb-3">
                                <span class="flex items-center gap-1">
                                    <svg class="size-3.5" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 1 1-7.5 0 3.75 3.75 0 0 1 7.5 0ZM4.501 20.118a7.5 7.5 0 0 1 14.998 0A17.933 17.933 0 0 1 12 21.75c-2.676 0-5.216-.584-7.499-1.632Z" /></svg>
                                    {{ $post->author->name ?? 'Autor' }}
                                </span>
                                <span>•</span>
                                <span>{{ $post->published_at->isoFormat('D MMM, YYYY') }}</span>
                            </div>

                            <h2 class="text-lg font-bold text-gray-900 mb-2">
                                <a href="{{ route('blog.show', $post) }}" class="hover:text-green-800 transition-colors">
                                    {{ $post->title }}
                                </a>
                            </h2>

                            @if($post->excerpt)
                                <p class="text-sm text-gray-600 leading-relaxed flex-1">{{ $post->excerpt }}</p>
                            @endif

                            <div class="mt-4 pt-4 border-t border-gray-100">
                                <a href="{{ route('blog.show', $post) }}" class="inline-flex items-center gap-1.5 text-sm font-semibold text-green-800 hover:text-green-700 transition-colors">
                                    Leer más
                                    <svg class="size-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5 21 12m0 0-7.5 7.5M21 12H3" /></svg>
                                </a>
                            </div>
                        </div>
                    </article>
                @endforeach
            </div>

            <div class="mt-10">
                {{ $posts->links() }}
            </div>
        @else
            <div class="text-center py-20">
                <svg class="size-16 mx-auto text-gray-300 mb-4" fill="none" viewBox="0 0 24 24" stroke-width="1" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M12 7.5h1.5m-1.5 3h1.5m-7.5 3h7.5m-7.5 3h7.5m3-9h3.375c.621 0 1.125.504 1.125 1.125V18a2.25 2.25 0 0 1-2.25 2.25M16.5 7.5V18a2.25 2.25 0 0 0 2.25 2.25M16.5 7.5V4.875c0-.621-.504-1.125-1.125-1.125H4.125C3.504 3.75 3 4.254 3 4.875V18a2.25 2.25 0 0 0 2.25 2.25h13.5M6 7.5h3v3H6v-3Z" /></svg>
                <h2 class="text-xl font-bold text-gray-500 mb-2">No hay artículos aún</h2>
                <p class="text-gray-400">Pronto publicaremos contenido nuevo. ¡Vuelve pronto!</p>
            </div>
        @endif
    </section>
@endsection

@extends('layouts.app', ['navbarActive' => 'blog'])

@section('title', $post->title . ' | ' . config('app.name', 'CBTA 254'))

@section('meta_description', $post->excerpt ?? Str::limit(strip_tags($post->content), 160))

@push('styles')
    <style>
        .prose h2 { @apply text-2xl font-bold text-gray-900 mt-8 mb-4; }
        .prose h3 { @apply text-xl font-bold text-gray-900 mt-6 mb-3; }
        .prose p { @apply text-gray-700 leading-relaxed mb-4; }
        .prose ul { @apply list-disc list-inside text-gray-700 space-y-1 mb-4; }
        .prose ol { @apply list-decimal list-inside text-gray-700 space-y-1 mb-4; }
        .prose blockquote { @apply border-l-4 border-green-800 bg-green-50 italic pl-4 py-2 my-4 text-gray-700; }
        .prose a { @apply text-green-800 underline hover:text-green-700; }
        .prose img { @apply rounded-xl my-6 w-full; }
    </style>
@endpush

@section('content')
    {{-- HERO --}}
    @php $welcome = \App\Models\WelcomeContent::first(); @endphp
    <section class="relative min-h-[60vh] flex items-center pt-16 lg:pt-20">
        <div class="absolute inset-0 z-0">
            <img src="{{ $welcome?->hero_image ? (Str::startsWith($welcome->hero_image, 'http') ? $welcome->hero_image : asset('storage/' . $welcome->hero_image)) : 'https://images.unsplash.com/photo-1588072432836-e10032774350?q=80&w=2072&auto=format&fit=crop' }}"
                alt="" class="size-full object-cover">
            <div class="section-gradient absolute inset-0"></div>
        </div>
        <div class="relative z-10 w-full max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-10 md:py-16">
            <div class="mb-4">
                <a href="{{ route('blog.index') }}" class="inline-flex items-center gap-1.5 text-sm font-medium text-green-200 hover:text-white transition-colors">
                    <svg class="size-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5 3 12m0 0 7.5-7.5M3 12h18" /></svg>
                    Volver al Blog
                </a>
            </div>
            <div class="flex items-center gap-3 text-sm text-green-200 mb-4">
                <span class="flex items-center gap-1.5">
                    <svg class="size-4" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 1 1-7.5 0 3.75 3.75 0 0 1 7.5 0ZM4.501 20.118a7.5 7.5 0 0 1 14.998 0A17.933 17.933 0 0 1 12 21.75c-2.676 0-5.216-.584-7.499-1.632Z" /></svg>
                    {{ $post->author->name ?? 'Autor' }}
                </span>
                <span>•</span>
                <span>{{ $post->published_at->isoFormat('D MMMM, YYYY') }}</span>
            </div>
            <h1 class="text-3xl sm:text-4xl lg:text-5xl font-bold text-white leading-tight">{{ $post->title }}</h1>
        </div>
    </section>

    {{-- CONTENT --}}
    <section class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-10 lg:py-14">
        @if($post->featured_image)
            <img src="{{ asset('storage/' . $post->featured_image) }}" alt="{{ $post->title }}" class="w-full rounded-2xl shadow-sm mb-10">
        @endif

        <div class="prose max-w-none">
            {!! $post->content !!}
        </div>

        <div class="mt-12 pt-8 border-t border-gray-200">
            <a href="{{ route('blog.index') }}" class="inline-flex items-center gap-2 text-sm font-semibold text-green-800 hover:text-green-700 transition-colors">
                <svg class="size-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5 3 12m0 0 7.5-7.5M3 12h18" /></svg>
                Volver al Blog
            </a>
        </div>
    </section>
@endsection

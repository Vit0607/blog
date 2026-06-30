@extends('layouts.app')

@section('title', 'Блог на Laravel 12')


@section('content')

    <main class="mx-auto max-w-6xl px-4 py-10">
        <div class="flex flex-col gap-4 bg-gray-900/40 p-6 rounded-2xl">
            <div>
                <a href="{{ route('blog.index') }}"
                    class="inline-flex items-center gap-2 rounded-xl border border-white/10 px-3 py-2 text-sm text-gray-300 transition hover:text-white">
                    &larr; Ко всем постам
                </a>
            </div>

            <article class="prose prose-invert max-w-none">
                <p class="text-xs text-gray-400 uppercase tracking-wide mb-4">
                    Опубликовано: {{ optional($post->published_at ?? $post->created_at)->format('d.m.Y') }}
                </p>

                @if ($post->image)
                    <div class="w-full rounded mb-4">
                        <img src="{{ $post->image_url }}" alt="{{ $post->title }}" class="w-full rounded" />
                    </div>
                @endif


                <h1 class="mb-4 text-3xl font-bold">{{ $post->title }}</h1>

                <div class="mt-6">
                    {!! $post->body !!}
                </div>
            </article>
        </div>
    </main>

@endsection

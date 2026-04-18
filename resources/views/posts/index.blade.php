@extends('layouts.app')

@section('title', 'Блог на Laravel 12')


@section('content')
<form action="{{ route('blog.index') }}" method="GET" class="my-4 flex max-w-md items-center gap-2">
    <input 
        type="text" 
        name="q" 
        value="{{ request('q') }}" 
        placeholder="Поиск по названию или тексту..." 
        class="flex-1 rounded-lg border border-white/10 bg-gray-900/40 px-3 py-2 text-sm text-gray-200 placeholder-gray-500 focus:border-fuchsia-500/50 focus:outline-none">
    <button type="submit" class="rounded-lg border border-fuchsia-500/30 bg-fuchsia-500/10 px-3 py-2 text-sm text-fuchsia-300 transition hover:bg-fuchsia-500/20 hover:text-white">
        Найти
    </button>
</form>

<div class="grid grid-cols-2 gap-4">
    @foreach($posts as $post)
    <article class="group relative overflow-hidden rounded-2xl border border-white/10 bg-gray-900/50 p-5 shadow transition hover:-translate-y-1 hover:shadow-lg hover:shadow-fuchsia-500/10">
        <a aria-label="читать далее" href="{{ route('blog.show', $post->slug) }}" class="absolute inset-0 z-10"></a>

        <div class="flex flex-col gap-3">
            <div class="text-xs uppercase tracking-wide text-gray-400">
                {{ $post->published_at?->format('d.m.Y')}}
            </div>

            @if($post->image)
            <div class="w-full rounded">
                <img src="{{ $post->image_url }}" alt="{{ $post->title }}" class="w-full rounded" />
            </div>
            @endif

            <h3 class="text-xl font-semibold leading-tight text-white">
                {{ $post->title }}
            </h3>
            <p class="text-gray-300">
                {{ $post->excerpt}}
            </p>

            <div class="pt-2">
                <a href="{{ route('blog.show', $post->slug) }}" class="inline-flex items-center gap-2 rounded-xl border border-fuchsia-500/30 bg-fuchsia-500/10 px-3 py-2 text-sm text-fuchsia-300 transition group-hover:bg-fuchsia-500/20 group-hover:text-white">
                    Читать далее
                </a>
            </div>
        </div>
    </article>
    @endforeach
</div>

@if($posts->hasPages())
{{ $posts->onEachSide(1)->links('components.pagination') }}
@endif


@endsection

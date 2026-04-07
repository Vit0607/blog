@extends('admin.layouts.app')

@section('title', 'Редактирование поста')

@section('content')
<h1 class="text-xl font-bold">Редактирование поста</h1>

<form class="glass rounded-2xl p-6 border border-white/10 space-y-5 flex flex-col gap-3" action="{{ route('posts.update', $post->id) }}" method="POST">
    @csrf
    @method('PUT')
    <div class="flex flex-col">
        <label class="label">Заголовок</label>
        <input class="input border px-3 py-1 rounded-xl" placeholder="Название поста" name="title" value="{{ old('title', $post->title) }}" />
        @error('title') <p class="text-red-400 text-sm mt-1">{{ $message }}</p> @enderror
    </div>

    <div class="flex flex-col">
        <label class="label">Краткое описание</label>
        <textarea class="input border px-3 py-1 rounded-xl" rows="3" placeholder="Тизер для списка постов..." name="excerpt" />{{ old('excerpt', $post->excerpt) }}</textarea>
        @error('excerpt') <p class="text-red-400 text-sm mt-1">{{ $message }}</p> @enderror
    </div>

    <div class="flex flex-col">
        <label class="label">Текст</label>
        <textarea class="input border px-3 py-1 rounded-xl" rows="10" placeholder="Основной контент..." name="body" />{{ old('body', $post->body) }}</textarea>
        @error('body') <p class="text-red-400 text-sm mt-1">{{ $message }}</p> @enderror
    </div>

    <div class="grid sm:grid-cols-2 gap-4">
        <div class="flex flex-col">
            <label class="label">Статус</label>
            <input type="checkbox" name="is_published" {{ old('is_published', $post->is_published ?? false) ? 'checked' : '' }} />
        </div>
    </div>

    <div class="flex items-center justify-end gap-2">
        <a href="{{ route('posts.index') }}" class="btn btn-outline cursor-pointer">Отмена</a>
        <button class="btn btn-primary px-4 py-2 cursor-pointer rounded-xl" type="submit">Обновить</button>
    </div>
</form>

@endsection

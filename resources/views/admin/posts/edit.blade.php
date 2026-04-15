@extends('admin.layouts.app')

@section('title', 'Редактирование поста')

@section('content')
<h1 class="text-xl font-bold">Редактирование поста</h1>

<form class="glass rounded-2xl p-6 border border-white/10 space-y-5 flex flex-col gap-3" action="{{ route('admin.posts.update', $post->id) }}" method="POST" enctype="multipart/form-data">
    @csrf
    @method('PUT')

    <div class="flex flex-col">
        <label class="label">Изображение</label>
        <input type="file" name="image" accept="image/*" class="mt-1 block w-full rounded border border-white/10 bg-gray-900/40 p-2 cursor-pointer" />
        @error('image') <p class="text-red-400 text-sm mt-1">{{ $message }}</p> @enderror
    </div>

    @if(isset($post) && $post->image)
    <div class="mt-3">
        <p class="text-sm text-gray-400">Текущее изображение</p>
        <img src="{{ $post->image_url }}" alt="Превью поста" class="mt-2 h-32 rounded object-cover" />

        <label class="mt-2 flex items-center gap-2 text-sm cursor-pointer">
            <input type="checkbox" name="remove_image" value="1" class="rounded border-gray-300">
            <span>Удалить изображение</span>
        </label>
    </div>
    @endif

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
        <a href="{{ route('admin.posts.index') }}" class="btn btn-outline cursor-pointer">Отмена</a>
        <button class="btn btn-primary px-4 py-2 cursor-pointer rounded-xl" type="submit">Обновить</button>
    </div>
</form>

@endsection

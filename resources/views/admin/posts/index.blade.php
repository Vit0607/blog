@extends('admin.layouts.app')

@section('title', 'Список постов Laravel 12')

@section('content')
<div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
    <div>
        <h1 class="text-xl font-bold">Посты</h1>
        <p class="text-sm text-gray-400">Управляйте контентом блога</p>
    </div>
    <a href="{{ route('posts.create') }}" class="btn btn-primary cursor-pointer p-1 rounded-xl">+ Новый пост</a>
</div>

<div class="glass rounded-2xl p-4 border border-white/10">
    <div class="grid sm:grid-cols-2 lg:grid-cols-4 gap-3">
        <input class="input" placeholder="Поиск по заголовку...">
        <select class="input">
            <option>Статус: все</option>
            <option>черновик</option>
            <option>Опубликован</option>
        </select>
        <select class="input">
            <option>Сортировка: новые</option>
            <option>Старые</option>
            <option>По заголовку A-Z</option>
        </select>
        <button class="btn btn-outline">Сбросить</button>
    </div>
</div>

<div class="overflow-hidden rounded-2xl border border-white/10">
    <table class="w-full text-sm">
        <thead class="bg-white/5 text-gray-300">
            <tr>
                <th class="text-left px-4 py-3">ID</th>
                <th class="text-left px-4 py-3">Заголовок</th>
                <th class="text-left px-4 py-3">Статус</th>
                <th class="text-left px-4 py-3">Дата</th>
                <th class="text-right px-4 py-3">Действия</th>
            </tr>
        </thead>
        <tbody class="[&>tr:nth-child(even)]:bg-white/5">
            @foreach ($posts as $post)
            <tr>
                <td class="px-4 py-3">{{ $post->id }}</td>
                <td class="px-4 py-3"><a href="{{ route('posts.show', $post->id) }}">{{ $post->title }}</a></td>
                <td class="px-4 py-3"><span class="badge">{{ $post->is_published ? 'Опубликован' : 'В драфте'}}</span></td>
                <td class="px-4 py-3">{{ $post->published_at?->format('d.m.Y') }}</td>
                <td class="px-4 py-3">
                    <div class="flex items-center gap-2 justify-end">
                        <a href="{{ route('posts.edit', $post->id) }}" class="btn btn-outline">Редактировать</a>
                        <form action="{{ route('posts.destroy', $post->id) }}" method="POST">
                            @csrf
                            @method('DELETE')
                            <button type="submit" data-modal="#del-3" class="btn btn-outline cursor-pointer">Удалить</button>
                        </form>
                    </div>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>

<div class="flex items-center justify-center gap-2">
    {{ $posts->links() }}
</div>

@endsection

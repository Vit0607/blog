<?php

use App\Models\Post;
use Livewire\Component;
use Livewire\WithPagination;

new class extends Component
{
    use WithPagination;

    public $search = '';

    public $status = '';

    public $sort = 'newest';

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function updatingStatus()
    {
        $this->resetPage();
    }

    public function updatingSort()
    {
        $this->resetPage();
    }

    public function deletePost($id)
    {
        Post::destroy($id);
    }

    public function with(): array
    {
        return [
            'posts' => Post::query()
                ->when($this->search, function ($query) {
                    $query->where('title', 'like', '%'.$this->search.'%');
                })
                ->when($this->status, function ($query) {
                    if ($this->status === 'published') {
                        $query->where('is_published', true);
                    }
                    if ($this->status === 'draft') {
                        $query->where('is_published', false);
                    }
                })
                ->when($this->sort, function ($query) {
                    match ($this->sort) {
                        'oldest' => $query->orderBy('created_at', 'asc'),
                        'title_asc' => $query->orderBy('title', 'asc'),
                        default => $query->orderBy('created_at', 'desc'),
                    };
                })
                ->paginate(10),
        ];
    }
};
?>

<div>
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3 mb-4">
        <div>
            <h1 class="text-xl font-bold">Посты</h1>
            <p class="text-sm text-gray-400">Управляйте контентом блога</p>
        </div>
        <a href="{{ route('admin.posts.create') }}" class="btn btn-primary cursor-pointer p-1 rounded-xl">+ Новый пост</a>
    </div>

    <div class="glass rounded-2xl p-4 border border-white/10 mb-4" style="background-color: #ffffff0d;">
        <div class="grid sm:grid-cols-2 lg:grid-cols-4 gap-3">

            <input wire:model.live="search" class="input text-white" placeholder="Поиск по заголовку...">

            <select wire:model.live="status" class="input text-white bg-transparent cursor-pointer">
                <option class="bg-[#000] text-white" value="">Статус: все</option>
                <option class="bg-[#000] text-white" value="draft">Черновик</option>
                <option class="bg-[#000] text-white" value="published">Опубликован</option>
            </select>

            <select wire:model.live="sort" class="input text-white bg-transparent cursor-pointer">
                <option class="bg-[#000] text-white" value="newest">Сортировка: новые</option>
                <option class="bg-[#000] text-white" value="oldest">Старые</option>
                <option class="bg-[#000] text-white" value="title_asc">По заголовку A-Z</option>
            </select>

            <button wire:click="$set('search', ''); $set('status', ''); $set('sort', 'newest')"
                class="btn btn-outline cursor-pointer">Сбросить</button>
        </div>
    </div>

    <style>
        html {
            scrollbar-gutter: stable !important;
        }
    </style>

    <div class="overflow-hidden rounded-2xl border border-white/10 w-full overflow-x-auto">

        <table class="w-full text-sm table-fixed min-w-[900px]" style="table-layout: fixed !important;">
            <thead class="bg-white/5 text-gray-300">
                <tr>
                    <th class="text-left px-8 py-3 w-[80px]" style="width: 80px !important;">ID</th>
                    <th class="text-left px-4 py-3 w-auto">Заголовок</th>
                    <th class="text-left px-4 py-3 w-[140px]" style="width: 140px !important;">Статус</th>
                    <th class="text-left px-4 py-3 w-[220px]" style="width: 220px !important;">Дата создания /
                        Публикации</th>
                    <th class="text-left px-4 py-3 w-[230px]" style="width: 230px !important;">Действия</th>
                </tr>
            </thead>
            <tbody class="[&>tr:nth-child(even)]:bg-white/5">
                @foreach ($posts as $post)
                    <tr wire:key="post-{{ $post->id }}">
                        <td class="px-8 py-3 truncate text-gray-400 font-mono">{{ $post->id }}</td>
                        <td class="px-4 py-3 truncate">
                            <a href="{{ route('admin.posts.show', $post->id) }}"
                                class="hover:text-blue-400 transition-colors">
                                {{ $post->title }}
                            </a>
                        </td>
                        <td class="px-4 py-3 truncate">
                            <span class="badge inline-block text-center text-xs w-full">
                                {{ $post->is_published ? 'Опубликован' : 'В драфте' }}
                            </span>
                        </td>
                        <td class="px-4 py-3 truncate">
                            <div class="text-gray-200">{{ $post->created_at->format('d.m.Y H:i') }}</div>
                            @if ($post->published_at)
                                <div class="text-xs text-gray-400">Публ: {{ $post->published_at->format('d.m.Y') }}
                                </div>
                            @else
                                <div class="text-xs text-amber-500/80">Не опубликован</div>
                            @endif
                        </td>
                        <td class="px-4 py-3 truncate">
                            <div class="flex flex-col sm:flex-row sm:items-center gap-2 justify-start w-full">
                                <a href="{{ route('admin.posts.edit', $post->id) }}"
                                    class="btn btn-outline cursor-pointer text-center">Редактировать</a>
                                <button wire:click="deletePost({{ $post->id }})" wire:confirm="Вы уверены?"
                                    class="btn btn-outline cursor-pointer">Удалить</button>
                            </div>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>




    <div class="mt-4">
        {{ $posts->links() }}
    </div>
</div>

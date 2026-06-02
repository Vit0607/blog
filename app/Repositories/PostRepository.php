<?php

namespace App\Repositories;

use App\Models\Post;
use App\Repositories\Interfaces\PostRepositoryInterface;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;

class PostRepository implements PostRepositoryInterface
{
    public function getPublishedPaginated(?string $search, int $perPage): LengthAwarePaginator
    {
        $query = Post::query()->where('is_published', true);

        if ($search = trim((string) $search)) {
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                    ->orWhere('body', 'like', "%{$search}%");
            });
        }

        return $query->orderByDesc('published_at')
            ->orderByDesc('created_at')
            ->paginate($perPage)
            ->withQueryString();
    }

    public function findPublishedBySlugOrFail(string $slug): Post
    {
        return Post::query()->where('slug', $slug)
            ->where('is_published', true)
            ->firstOrFail();
    }

    public function getPaginated(int $perPage): LengthAwarePaginator
    {
        return Post::latest()->paginate($perPage);
    }

    public function allApi(): Collection
    {
        return Post::latest()->get();
    }

    public function findApi(int $id): ?Post
    {
        return Post::find($id);
    }

    public function createApi(array $data): Post
    {
        return Post::create($data);
    }

    public function updateApi(Post $post, array $data): Post
    {
        $post->update($data);

        return $post;
    }

    public function softDelete(Post $post): bool
    {
        return $post->delete();
    }

    public function restore(int $id): ?Post
    {
        $post = Post::onlyTrashed()->find($id);
        if (! $post) {
            return null;
        }

        $post->restore();

        return $post;
    }

    public function forceDelete(int $id): bool
    {
        $post = Post::find($id);
        if (! $post) {
            return false;
        }

        return $post->forceDelete();
    }

    public function getTrashed(): Collection
    {
        return Post::onlyTrashed()->get();
    }
}

<?php

namespace App\Services;

use App\Models\Post;
use App\Repositories\Interfaces\PostRepositoryInterface;
use App\Services\Interfaces\PostServiceInterface;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class PostService implements PostServiceInterface
{
    public function __construct(
        private PostRepositoryInterface $postRepository
    ){}

    public function create(array $data): Post
    {
        return DB::transaction(function () use ($data) {
            if (!empty($data['image'])) {
                $data['image'] = $data['image']->store('posts', 'public');
            }
            unset($data['remove_image']);

            $data['slug'] = Str::slug($data['title']) . '-' . Str::random(6);

            $isPublished = (bool)($data['is_published'] ?? false);
            $data['is_published'] = $isPublished;
            $data['published_at'] = $isPublished ? now() : null;

            return Post::create($data);
        });
    }

    public function update(Post $post, array $data): Post
    {
        return DB::transaction(function () use ($post, $data) {
            $newImage = $data['image'] ?? null;
            $removeImage = (bool)($data['remove_image'] ?? false);
            unset($data['image'], $data['remove_image']);
            
            if (isset($data['title']) && $data['title'] !== $post->title) {
                $data['slug'] = Str::slug($data['title']) . '-' . Str::random(6);
            }

            $wasPublished = (bool)$post->is_published;
            $nowPublished = (bool)($data['is_published'] ?? $wasPublished);
            $data['is_published'] = $nowPublished;

            if (!$wasPublished && $nowPublished) {
                $data['published_at'] = now();
            } elseif ($wasPublished && !$nowPublished) {
                $data['published_at'] = null;
            }

            if (($removeImage || $newImage) && $post->image) {
                Storage::disk('public')->delete($post->image);
                $data['image'] = null;
            }

            if ($newImage) {
                $data['image'] = $newImage->store('posts', 'public');
            }

            $post->update($data);

            return $post;
        });
    }

    public function delete(Post $post): void
    {
        DB::transaction(function () use ($post) {
            if ($post->image) {
                Storage::disk('public')->delete($post->image);
            }
            $post->delete();
        });
    }

    public function getAllApi(): Collection
    {
        return $this->postRepository->allApi();
    }

    public function getByIdApi(int $id): ?Post
    {
        return $this->postRepository->findApi($id);
    }

    public function createApi(array $data): Post
    {   
        $slug = Str::slug($data['title']);
        $data['slug'] = $slug;
        return $this->postRepository->createApi($data);
    }

    public function updateApi(int $id, array $data): ?Post
    {
        $post = $this->postRepository->findApi($id);

        if (!$post) {
            return null;
        }

        $slug = Str::slug($data['title']);
        $data['slug'] = $slug;
        return $this->postRepository->updateApi($post, $data);
    }

    public function deleteApi(int $id): bool
    {
        $post = $this->postRepository->findApi($id);

        if (!$post) {
            return false;
        }

        return $this->postRepository->deleteApi($post);
    }
}
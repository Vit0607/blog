<?php

namespace App\Services;

use App\Exceptions\ApiException;
use App\Models\Post;
use App\Repositories\Interfaces\PostRepositoryInterface;
use App\Services\Interfaces\PostServiceInterface;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class PostService implements PostServiceInterface
{
    public function __construct(
        private PostRepositoryInterface $postRepository
    ) {}

    public function create(array $data): Post
    {
        $storedImage = null;

        try {
            return DB::transaction(function () use ($data, &$storedImage) {
                if (! empty($data['image'])) {
                    $storedImage = $data['image']->store('posts', 'public');
                    $data['image'] = $storedImage;
                }

                unset($data['remove_image']);

                $data['slug'] = Str::slug($data['title']).'-'.Str::random(6);

                $isPublished = (bool) ($data['is_published'] ?? false);
                $data['is_published'] = $isPublished;
                $data['published_at'] = $isPublished ? now() : null;

                $post = Post::create($data);

                Cache::forget('posts_all');
                Cache::forget("posts_{$post->id}");

                return $post;
            });
        } catch (\Throwable $e) {
            if ($storedImage) {
                Storage::disk('public')->delete($storedImage);
            }

            throw $e;
        }
    }

    public function update(Post $post, array $data): Post
    {
        $newStoredImage = null;
        $oldImage = $post->image;

        try {
            return DB::transaction(function () use ($post, $data, &$newStoredImage, $oldImage) {
                $newImage = $data['image'] ?? null;
                $removeImage = (bool) ($data['remove_image'] ?? false);

                unset($data['image'], $data['remove_image']);

                if (isset($data['title']) && $data['title'] !== $post->title) {
                    $data['slug'] = Str::slug($data['title']).'-'.Str::random(6);
                }

                $wasPublished = (bool) $post->is_published;
                $nowPublished = (bool) ($data['is_published'] ?? $wasPublished);
                $data['is_published'] = $nowPublished;

                if (! $wasPublished && $nowPublished) {
                    $data['published_at'] = now();
                } elseif ($wasPublished && ! $nowPublished) {
                    $data['published_at'] = null;
                }

                if ($newImage) {
                    $newStoredImage = $newImage->store('posts', 'public');
                    $data['image'] = $newStoredImage;
                } elseif ($removeImage) {
                    $data['image'] = null;
                }

                $post->update($data);

                Cache::forget('posts_all');
                Cache::forget("posts_{$post->id}");

                if (($newImage || $removeImage) && $oldImage) {
                    DB::afterCommit(function () use ($oldImage) {
                        Storage::disk('public')->delete($oldImage);
                    });
                }

                return $post->refresh();
            });
        } catch (\Throwable $e) {
            if ($newStoredImage) {
                Storage::disk('public')->delete($newStoredImage);
            }

            throw $e;
        }
    }

    public function delete(Post $post): void
    {
        $image = $post->image;
        $postId = $post->id;

        DB::transaction(function () use ($post, $image, $postId) {
            $post->delete();

            Cache::forget('posts_all');
            Cache::forget("posts_{$postId}");

            if ($image) {
                DB::afterCommit(function () use ($image) {
                    Storage::disk('public')->delete($image);
                });
            }
        });
    }

    public function getTrashed(): Collection
    {
        return Cache::remember('posts_trash', 60, function () {
            return $this->postRepository->getTrashed();
        });
    }

    public function getAllApi(): Collection
    {
        return Cache::remember('posts_all', 60, function () {
            return $this->postRepository->allApi();
        });
    }

    public function getByIdApi(int $id): ?Post
    {
        return Cache::remember("posts_{$id}", 60, function () use ($id) {
            $post = $this->postRepository->findApi($id);

            if (! $post) {
                throw new ApiException("Post with ID $id not found", 404);
            }

            return $post;
        });
    }

    public function createApi(array $data): Post
    {
        if (! isset($data['title'])) {
            throw new \InvalidArgumentException('Title is required');
        }

        $data['slug'] = Str::slug($data['title']).'-'.Str::random(6);

        $post = $this->postRepository->createApi($data);

        Log::info('Post created', [
            'post_id' => $post->id,
            'user_id' => $post->user_id,
        ]);

        Cache::forget('posts_all');
        Cache::forget("posts_{$post->id}");

        $id = $post->id;

        return Cache::tags(['posts'])->remember("post_{$id}", 120, function () use ($id) {
            return $this->postRepository->findApi($id);
        });
    }

    public function updateApi(int $id, array $data): ?Post
    {
        $post = $this->postRepository->findApi($id);

        if (! $post) {
            return null;
        }

        if (isset($data['title'])) {
            $data['slug'] = Str::slug($data['title']).'-'.Str::random(6);
        }

        $updatedPost = $this->postRepository->updateApi($post, $data);

        Cache::forget('posts_all');
        Cache::forget("posts_{$id}");

        return $updatedPost;
    }

    public function softDeleteApi(int $id): bool
    {
        $post = $this->postRepository->findApi($id);

        if (! $post) {
            return false;
        }

        $deletedPost = $this->postRepository->softDelete($post);

        Cache::forget('posts_all');
        Cache::forget("posts_{$id}");

        return $deletedPost;
    }

    public function restoreApi(int $id): ?Post
    {
        $post = $this->postRepository->restore($id);
        if (! $post) {
            return null;
        }

        Cache::forget('posts_all');
        Cache::forget("posts_{$id}");

        return $post;
    }

    public function forceDeleteApi(int $id): bool
    {
        $deleted = $this->postRepository->forceDelete($id);
        if (! $deleted) {
            return false;
        }

        Cache::forget('posts_all');
        Cache::forget("posts_{$id}");

        return true;
    }
}

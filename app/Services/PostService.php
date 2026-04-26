<?php

namespace App\Services;

use App\Models\Post;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class PostService
{
    public function create(array $data): Post
    {
        return DB::transaction(function () use ($data) {
            $image = $data['image'] ?? null;
            unset($data['image'], $data['remove_image']);

            $slugBase = Str::slug($data['title']);
            $slug = $slugBase . '-' . Str::random(6);
            $data['slug'] = $slug;

            
            $isPublished = (bool)($data['is_published'] ?? false);
            $data['is_published'] = $isPublished;
            $data['published_at'] = $isPublished ? now() : null;

            $post = Post::create($data);

            if($image) {
                $path = $image->store('posts', 'public');
                $post->image = $path;
                $post->save();
            }

            return $post;
        });
    }

    public function update(Post $post, array $data): Post
    {
        return DB::transaction(function () use ($post, $data) {
            $newImage = $data['image'] ?? null;
            $removeImage = (bool)($data['remove_image'] ?? false);
            unset($data['image'], $data['remove_image']);
            
            $wasPublished = (bool)$post->is_published;
            $nowPublished = (bool)($data['is_published'] ?? $wasPublished);
            $data['is_published'] = $nowPublished;

            if (!$wasPublished && $nowPublished) {
                $data['published_at'] = now();
            } elseif ($wasPublished && !$nowPublished) {
                $data['published_at'] = null;
            }

            $slugBase = Str::slug($data['title']);
            $slug = $slugBase . '-' . Str::random(6);
            $data['slug'] = $slug;

            $post->update($data);

            if($removeImage && $post->image) {
                Storage::disk('public')->delete($post->image);
                $post->image = null;
            }

            if($newImage) {
                if($post->image) {
                     Storage::disk('public')->delete($post->image);
                }
                $post->image = $newImage->store('posts', 'public');
            }

            $post->save();

            return $post;
        });
    }

    public function delete(Post $post): void
    {
        DB::trasaction(function () use ($post) {
            if ($post->image) {
                Storage::disk('public')->delete($post->image);
            }
            $post->delete();
        });
    }
}
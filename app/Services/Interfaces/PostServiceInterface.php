<?php

namespace App\Services\Interfaces;

use App\Models\Post;
use Illuminate\Database\Eloquent\Collection;

interface PostServiceInterface
{
    public function create(array $data): Post;

    public function update(Post $post, array $data): Post;

    public function delete(Post $post): void;

    public function getTrashed(): Collection;

    public function getAllApi(): Collection;

    public function getByIdApi(int $id): ?Post;

    public function createApi(array $data): Post;

    public function updateApi(int $id, array $data): ?Post;

    public function softDeleteApi(int $id): bool;

    public function restoreApi(int $id): ?Post;

    public function forceDeleteApi(int $id): bool;
}

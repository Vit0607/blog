<?php

namespace App\Repositories\Interfaces;

use App\Models\Post;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;

interface PostRepositoryInterface
{
    public function getPublishedPaginated(?string $search, int $perPage): LengthAwarePaginator;

    public function findPublishedBySlugOrFail(string $slug): Post;

    public function getPaginated(int $perPage): LengthAwarePaginator;

    public function allApi(): Collection;

    public function findApi(int $id): ?Post;

    public function createApi(array $data): Post;

    public function updateApi(Post $post, array $data): Post;

    public function deleteApi(Post $post): bool;
}
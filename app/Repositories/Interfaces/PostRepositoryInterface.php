<?php

namespace App\Repositories\Interfaces;

use App\Models\Post;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

interface PostRepositoryInterface
{
    public function getPublishedPaginated(?string $search, int $perPage): LengthAwarePaginator;

    public function findPublishedBySlugOrFail(string $slug): Post;

    public function getPaginated(int $perPage): LengthAwarePaginator;
}
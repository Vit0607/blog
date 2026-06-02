<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StorePostRequest;
use App\Http\Requests\UpdatePostRequest;
use App\Http\Resources\PostCollection;
use App\Http\Resources\PostResource;
use App\Services\Interfaces\PostServiceInterface;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Gate;

class PostController extends Controller
{
    public function __construct(
        private PostServiceInterface $postService
    ) {}

    public function index(): PostCollection
    {
        $posts = $this->postService->getAllApi();

        return new PostCollection($posts);
    }

    public function trashed(): PostCollection
    {
        $posts = $this->postService->getTrashed();

        return new PostCollection($posts);
    }

    public function show(int $id): JsonResponse|PostResource
    {
        $post = $this->postService->getByIdApi($id);

        if (! $post) {
            return response()->json(['message' => 'Not found'], 404);
        }

        return new PostResource($post);
    }

    public function store(StorePostRequest $request): PostResource
    {
        $data = $request->validated();
        $data['user_id'] = $request->user()->id;

        $post = $this->postService->createApi($data);

        return new PostResource($post);
    }

    public function update(UpdatePostRequest $request, int $id): JsonResponse|PostResource
    {
        $post = $this->postService->getByIdApi($id);

        if (! $post) {
            return response()->json(['message' => 'Not found'], 404);
        }

        Gate::authorize('update', $post);

        $post = $this->postService->updateApi($id, $request->validated());

        if (! $post) {
            return response()->json(['message' => 'Not found'], 404);
        }

        return new PostResource($post);
    }

    public function destroy(int $id): JsonResponse
    {
        $post = $this->postService->getByIdApi($id);

        Gate::authorize('delete', $post);

        $deleted = $this->postService->softDeleteApi($id);

        if (! $deleted) {
            return response()->json(['message' => 'Not found'], 404);
        }

        return response()->json(['message' => 'Deleted']);
    }

    public function restore(int $id): JsonResponse
    {
        $result = $this->postService->restoreApi($id);

        if (! $result) {
            return response()->json(['success' => false, 'message' => 'Not Found'], 404);
        }

        return response()->json(['success' => true]);
    }

    public function forceDelete(int $id): JsonResponse
    {
        $deleted = $this->postService->forceDeleteApi($id);

        if (! $deleted) {
            return response()->json(['success' => false, 'message' => 'Not Found'], 404);
        }

        return response()->json(['success' => true]);
    }
}

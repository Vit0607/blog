<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StorePostRequest;
use App\Http\Requests\UpdatePostRequest;
use App\Services\Interfaces\PostServiceInterface;
use Illuminate\Http\JsonResponse;

class PostController extends Controller
{
    public function __construct(
        private PostServiceInterface $postService
    ){}

    public function index(): JsonResponse
    {
        return response()->json($this->postService->getAllApi());

    }

    public function show(int $id): JsonResponse
    {
        $post = $this->postService->getByIdApi($id);

        if (!$post) {
            return response()->json(['message', 'Not found'], 404);
        }

        return response()->json($post);
    }

    public function store(StorePostRequest $request): JsonResponse
    {
        $data = $request->validated();
        $data['user_id'] = $request->user()->id;
        
        return response()->json($this->postService->createApi($data), 201);
    }

    public function update(UpdatePostRequest $request, int $id): JsonResponse
    {
        $post = $this->postService->updateApi($id, $request->validated());

        if (!$post) {
            return response()->json(['message', 'Not found'], 404);
        }

        return response()->json($post);
    }

    public function destroy(int $id): JsonResponse
    {
        $deleted = $this->postService->deleteApi($id);

        if (!$deleted) {
            return response()->json(['message' => 'Not found']);
        }

        return response()->json(['message' => 'Deleted']);
    }
}
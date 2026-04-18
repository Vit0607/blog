<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\View\View;

class PostController extends Controller
{
    public function index(Request $request): View
    {
        $query = Post::query()->where('is_published', true);

        if ($search = trim((string)$request->input('q'))) {
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                    ->orWhere('body', 'like', "%{$search}%");
            });
        }

        $posts = $query->orderByDesc('published_at')
            ->orderByDesc('created_at')
            ->paginate(4)
            ->withQueryString();

        return view('posts.index', compact('posts'));
    }

    public function show(Post $post): View
    {
        abort_unless($post->is_published, 404);

        return view('posts.show', compact('post'));
    }
}
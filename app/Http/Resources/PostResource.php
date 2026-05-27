<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;

/**
 * @mixin \App\Models\Post
 */

class PostResource extends BaseResource
{
    public function toArray(Request $request): array
    {   
        return [
            'id' => $this->id,
            'title' => $this->title,
            'slug' => $this->slug,
            'excerpt' => $this->excerpt,
            'body' => $this->when($request->user()->id === $this->user_id, $this->body),
            'is_published' => $this->is_published,
            'published_at' => $this->published_at,
            'created_at_human' => $this->created_at->diffForHumans(),
            'admin_only' => $this->mergeWhen($request->user()->role->value === 'admin', [
                'ip' => $this->ip_address
            ])
        ];
    }
}
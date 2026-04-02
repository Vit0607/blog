<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $post->title }}</title>
</head>
<body>
    <a href="{{ route('posts.index') }}">Назад к постам</a>

    <div>
        <h1>{{ $post->title }}</h1>
        <p>
            {{ $post->published_at?->format('d.m.Y') }}
        </p>
    </div>

    <div>
        {!! nl2br(e($post->body)) !!}
    </div>
</body>
</html>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Блог на Laravel</title>
</head>
<body>
    <h1>Блог на Laravel</h1>

    @foreach ($posts as $post)
    <div>
        <h2>
            <a href="{{ route('posts.show', $post->slug) }}">{{ $post->title }}</a>
        </h2>
        <p>
            {{$post->published_at?->format('d.m.Y')}}
        </p>
        <p>{{ Str::limit($post->excerpt, 150) }}</p>
        <a href="{{ route('posts.show', $post->slug) }}">Читать дальше</a>
    </div>
    @endforeach;

    <div>
        {{ $posts->links() }}
    </div>

</body>
</html>

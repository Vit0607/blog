<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'Мой блог')</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-screen bg-gray-950 text-gray-100 antialiased">
    <header class="border-b border-white/10 bg-gray-900/60 backdrop-blur">
        <div class="mx-auto max-w-6xl px-4 py-4 flex items-center justify-between">
            <a href="{{ route('index') }}" class="text-lg font-semibold tracking-wide text-white">
                Мой Блог
            </a>

            <nav class="flex items-center gap-6 text-sm text-gray-300">
                <a href="{{ route('blog.index') }}" class="hover:text-white transition">Посты</a>
                <a href="#" class="hover:text-white transition">О проекте</a>

                @auth
                <a href="{{ route('admin.posts.index') }}">Dashboard</a>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button>Выйти</button>
                </form>
                @endauth
                @guest
                <a href="{{ route('login') }}">Войти</a>
                <a href="{{ route('register') }}">Регистрация</a>
                @endguest
            </nav>
        </div>
    </header>
    <main class="mx-auto max-w-6xl px-4 py-10">
        @yield('content')
    </main>

</body>
</html>

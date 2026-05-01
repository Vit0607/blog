@extends('layouts.app')

@section('title', 'Авторизация в блог на Laravel 12')

@section('content')
<div class="mx-auto max-w-md">
    <h1 class="mb-6 text-2xl font-bold">Вход</h1>

    <form action="{{ route('login.post') }}" method="POST" class="space-y-4">
        @csrf
        <div>
            <label class="mb-1 block text-sm">Email</label>
            <input type="email" name="email" value="{{ old('email') }}" class="w-full rounded border px-3 py-2" required autofocus>
            @error('email') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
        </div>

        <div>
            <label class="mb-1 block text-sm">Пароль</label>
            <input type="password" name="password" class="w-full rounded border px-3 py-2" required>
            @error('password') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
        </div>

        <label class="flex items-center gap-2 text-sm">
            <input type="checkbox" name="remember_me" value="1" class="rounded">
            Запомнить меня
        </label>

        <button class="w-full rounded bg-slate-900 px-4 py-2 text-white hover:bg-slate-800">
            Войти
        </button>
    </form>

    <p class="mt-4 text-sm text-slate-600">
        Нет аккаунта? <a class="text-white underline" href="{{ route('register') }}">Зарегистрируйтесь</a>
    </p>
</div>
@endsection

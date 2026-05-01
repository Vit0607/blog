@extends('layouts.app')

@section('title', 'Регистрация в блог на Laravel 12')

@section('content')
<div class="mx-auto max-w-md">
    <h1 class="mb-6 text-2xl font-bold">Регистрация</h1>

    <form action="{{ route('register.post') }}" method="POST" class="space-y-4">
        @csrf

        <div>
            <label class="mb-1 block text-sm">Имя</label>
            <input type="text" name="name" value="{{ old('name') }}" class="w-full rounded border px-3 py-2" required>
            @error('name') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
        </div>

        <div>
            <label class="mb-1 block text-sm">Email</label>
            <input type="email" name="email" value="{{ old('email') }}" class="w-full rounded border px-3 py-2" required>
            @error('email') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
        </div>

        <div>
            <label class="mb-1 block text-sm">Пароль</label>
            <input type="password" name="password" class="w-full rounded border px-3 py-2" required>
            @error('password') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
        </div>

        <div>
            <label class="mb-1 block text-sm">Подтверждение пароля</label>
            <input type="password" name="password_confirmation" class="w-full rounded border px-3 py-2" required>
        </div>

        <button class="w-full rounded bg-slate-900 px-4 py-2 text-white hover:bg-slate-800">
            Создать аккаунт
        </button>
    </form>

    <p class="mt-4 text-sm text-slate-600">
        Уже есть аккаунт? <a class="text-white underline" href="{{ route('login') }}">Войти</a>
    </p>
</div>
@endsection

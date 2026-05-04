@extends('Layouts.app')
@section('title', 'Восстановление пароля на Laravel 12')
@section('content')
<div class="max-w-md mx-auto">
    <h1 class="text-2xl font-bold mb-6">Новый пароль</h1>
    <form method="POST" action="{{ route('password.update') }}" class="space-y-4">
        @csrf
        <input type="hidden" name="token" value="{{ $token }}">
        <div>
            <label class="block mb-1 text-sm">Email</label>
            <input type="email" name="email" class="w-full border rounded px-3 py-2" value="{{ old('email') }}" required>
        </div>
        <div>
            <label class="block mb-1 text-sm">Новый пароль</label>
            <input type="password" name="password" class="w-full border rounded px-3 py-2" required>
        </div>
        <div>
            <label class="block mb-1 text-sm">Подтверждение</label>
            <input type="password" name="password_confirmation" class="w-full border rounded px-3 py-2" required>
        </div>
        <button class="bg-slate-900 text-white w-full py-2 rounded">
            Сохранить пароль
        </button>
    </form>
</div>
@endsection

@extends('layouts.app')
@section('title', 'Авторизация в блоге на Laravel 12')
@section('content')
    <div class="max-w-lg mx-auto mt-10">
        <h1 class="text-2xl font-bold mb-4">Подтвердите ваш email</h1>
        <p class="mb-4">
            Прежде чем продолжить, пожалуйста, проверьте свою почту.
            Если письмо не пришло — вы можете отправить его ещё раз.
        </p>
        @if (session('message'))
            <div class="p-3 bg-green-100 text-green-700 rounded mb-4">
                {{ session('message') }}
            </div>
        @endif
        <form method="POST" action="{{ route('verification.send') }}">
            @csrf
            <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">
                Отправить письмо повторно
            </button>
        </form>
    </div>
@endsection

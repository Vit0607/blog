<?php

namespace App\Http\Controllers;

use App\Http\Requests\Auth\LoginRequest;
use App\Http\Requests\Auth\RegisterRequest;
use App\Services\Interfaces\AuthServiceInterface;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class AuthController extends Controller
{
    public function __construct(
        private AuthServiceInterface $auth
    ){}

    public function showLogin(): View
    {
        return view('auth.login');
    }

    public function register(RegisterRequest $request): RedirectResponse
    {
        $this->auth->register($request->validated());

        $this->auth->attemptLogin($request->email, $request->password);

        $request->session()->regenerate();

        return redirect()->route('verification.notice');
    }

    public function login(LoginRequest $request): RedirectResponse
    {
        $valid = $request->validated();

        if(!$this->auth->attemptLogin($valid['email'], $valid['password'], (bool)($valid['remember_me'] ?? false))) {
            return back()->withErrors(['email' => 'Неверная почта или пароль'])->onlyInput();
        }

        $request->session()->regenerate();

        return redirect()->intended(route('admin.posts.index'))->with('status', 'С возвращением');
    }

    public function showRegister(): View
    {
        return view('auth.register');
    }

    public function logout(): RedirectResponse
    {
        $this->auth->logout();

        return redirect()->route('login')->with('status', 'Вы вышли из аккаунта');
    }
}
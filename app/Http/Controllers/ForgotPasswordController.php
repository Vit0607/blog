<?php

namespace App\Http\Controllers;

use App\Http\Requests\Auth\ForgotPasswordRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Password;
use Illuminate\View\View;
use Illuminate\Auth\Notifications\ResetPassword;
use Illuminate\Notifications\Messages\MailMessage;

class ForgotPasswordController extends Controller
{
    public function showForgotForm(): View
    {
        return view('auth.forgot-password');
    }

    public function sendResetLink(ForgotPasswordRequest $request): RedirectResponse
    {
        
        ResetPassword::toMailUsing(function ($notifiable, $token) {
            return (new MailMessage)
                ->subject('Сброс пароля')
                ->line('Вы получили это письмо, так как был запрошен сброс пароля для вашей учётной записи.')
                ->line('Ваша ссылка для сброса пароля: ' . url(config('app.url') . '/reset-password/' . $token))
                ->line('Ссылка действительна в течение 60 минут.')
                ->line('Если вы не запрашивали сброс пароля, просто проигнорируйте это письмо.');
        });
        
        
        $status = Password::sendResetLink(
            $request->only('email')
        );

        if ($status === Password::RESET_LINK_SENT) {
            return back()->with('status', __($status));
        }

        return back()->withError(['email' => __($status)]);
    }
}
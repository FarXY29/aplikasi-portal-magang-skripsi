<?php

namespace App\Providers;

use Illuminate\Auth\Notifications\ResetPassword;
use Illuminate\Auth\Notifications\VerifyEmail;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\URL;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        $publicUrl = config('app.public_url');

        if ($publicUrl) {
            $publicUrl = rtrim($publicUrl, '/');
            URL::forceRootUrl($publicUrl);
            URL::forceScheme(parse_url($publicUrl, PHP_URL_SCHEME) ?: 'https');
        } else {
            $forwardedHost = request()->header('x-forwarded-host');
            $forwardedProto = strtolower(trim(explode(',', request()->header('x-forwarded-proto', ''))[0]));

            // Ngrok meneruskan host publik melalui X-Forwarded-Host. Pakai host
            // tersebut agar QR yang dibuat dari request web tidak berisi localhost.
            if ($forwardedHost && $forwardedProto === 'https') {
                $publicHost = trim(explode(',', $forwardedHost)[0]);
                URL::forceRootUrl('https://' . $publicHost);
            }

            if ($forwardedProto === 'https' || str_contains(request()->header('host', ''), 'trycloudflare.com')) {
                URL::forceScheme('https');
            }
        }

        ResetPassword::toMailUsing(function (object $notifiable, string $token) {
            $url = url(route('password.reset', [
                'token' => $token,
                'email' => $notifiable->getEmailForPasswordReset(),
            ], false));

            return (new MailMessage)
                ->subject('Notifikasi Reset Password - Portal Magang Banjarmasin')
                ->view('emails.reset-password', ['url' => $url, 'user' => $notifiable]);
        });

        VerifyEmail::toMailUsing(function (object $notifiable, string $url) {
            return (new MailMessage)
                ->subject('Verifikasi Alamat Email - Portal Magang Banjarmasin')
                ->view('emails.verify-email', ['url' => $url, 'user' => $notifiable]);
        });
    }
}

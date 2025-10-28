<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class WelcomeNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public function via(object $notifiable): array
    {
        return ['mail', 'database'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        $displayName = $this->displayName($notifiable);

        return (new MailMessage())
            ->subject(__('Selamat datang di :app', ['app' => config('app.name')]))
            ->greeting(__('Halo :name!', ['name' => $displayName]))
            ->line(__('Akun Anda berhasil dibuat. Kami telah menyiapkan starter kit dengan berbagai fitur untuk membantu Anda mengelola konten dan pengguna.'))
            ->action(__('Masuk ke Dashboard'), url('/admin'))
            ->line(__('Jika Anda tidak melakukan pendaftaran, abaikan email ini.'));
    }

    public function toArray(object $notifiable): array
    {
        return [
            'title' => __('Selamat datang!'),
            'message' => __('Akun Anda di :app berhasil dibuat.', ['app' => config('app.name')]),
        ];
    }

    protected function displayName(object $notifiable): string
    {
        if (is_object($notifiable)) {
            foreach (['full_name', 'name', 'username', 'email'] as $attribute) {
                if (isset($notifiable->{$attribute}) && filled($notifiable->{$attribute})) {
                    return (string) $notifiable->{$attribute};
                }
            }
        }

        return __('Pengguna');
    }
}

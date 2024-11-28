<?php

namespace App\Notifications;

use Illuminate\Auth\Notifications\VerifyEmail;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class VerifyEmailNotification extends VerifyEmail
{
    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->subject('Verifica tu dirección de correo electrónico')
            ->line('Haz clic en el botón para verificar tu dirección de correo electrónico.')
            ->action('Verificar correo', $this->verificationUrl($notifiable));
    }
}

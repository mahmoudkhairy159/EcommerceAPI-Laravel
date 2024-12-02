<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class UserResetPasswordNotification extends Notification
{
    use Queueable;

    /**
     * The password reset URL.
     *
     * @var string
     */
    public $url;

    /**
     * The password reset token.
     *
     * @var string
     */
    public $token;
    public $email;

    /**
     * Create a new notification instance.
     *
     * @param  string  $url
     * @param  string  $token
     * @return void
     */
    public function __construct($url, $token, $email)
    {
        $this->url = $url;
        $this->token = $token;
        $this->email = $email;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        $url = $this->url . '?' . 'token=' . $this->token . '&email=' . $this->email;

        return (new MailMessage)
            ->subject('Reset Password Notification')
            ->line('You are receiving this email because we received a password reset request for your account.')
            ->action('Reset Password', $url)
            ->line('If you did not request a password reset, no further action is required.')
            ->line('If you\'re having trouble clicking the "Reset Password" button, copy and paste the URL below into your web browser:')
            ->line($url);
    }
}

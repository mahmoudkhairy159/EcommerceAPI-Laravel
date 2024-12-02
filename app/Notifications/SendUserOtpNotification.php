<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

class SendUserOtpNotification extends  Notification implements ShouldQueue
{
    use Queueable;


    protected $otpCode;

    public function __construct($otpCode)
    {
        $this->otpCode = $otpCode;
    }

    public function via($notifiable)
    {
        return ['mail'];
    }

    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->subject(__(('user::app.auth.otp.otp_email_subject')))
            ->line(__('user::app.auth.otp.your_otp_code_is', ['otp' => $this->otpCode]))
            ->line(__('user::app.auth.otp.otp_code_valid_for_x_minutes', ['minutes' => 5]));
    }


    public function toArray($notifiable)
    {
        return [
            'otpCode' => $this->otpCode
        ];
    }
}

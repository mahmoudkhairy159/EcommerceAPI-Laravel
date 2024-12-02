<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class SampleMail extends Mailable
{
    use Queueable, SerializesModels;

    public $msg;
    public $subject;

    public function __construct($msg, $subject)
    {
        $this->msg = $msg;
        $this->subject = $subject;
    }

    public function build()
    {
        return $this->subject($this->subject)->view('mails.sample');
    }
}

<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class resetMail extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     *
     * @return void
     * 
     */
    public $fetch;

    public function __construct($fetch)
    {
        $this->fetch = $fetch;
    }  

    public function build()
    {
        return $this->subject(" Enter the OTP to change password")
                       ->view('resetMail');
    }
}

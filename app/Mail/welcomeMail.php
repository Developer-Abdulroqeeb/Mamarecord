<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class welcomeMail extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public $user; 
 

    public function __construct( $user
    )
    {
        $this->user = $user;
    }

    public function build()
    {
        return $this->subject('Welcome to MamaRecord')
                    ->view('welcomeMail');
    }



  
}

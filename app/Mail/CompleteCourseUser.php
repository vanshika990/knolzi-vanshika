<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class CompleteCourseUser extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($userData)
    {
        $this->userData = $userData;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {

        return $this->view('emails.completecourseuser')->with([ 'data'  =>  $this->userData])->attach($this->userData['certificate']->getRealPath(),
        [
            'as' => $this->userData['certificate']->getClientOriginalName(),
            'mime' => $this->userData['certificate']->getClientMimeType(),
        ]);
    }
}

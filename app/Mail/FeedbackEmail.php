<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class FeedbackEmail extends Mailable {

    use Queueable,
        SerializesModels;

    protected $userData;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($userData) {
        $this->userData = $userData;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build() {

        return $this->view('emails.feedback')->with([
                    'feedback_type' => $this->userData['feedback_type'],
                    'user_email' => $this->userData['user_email'],
                    'feedback_message' => $this->userData['feedback_message'],
        ]);
    }

}

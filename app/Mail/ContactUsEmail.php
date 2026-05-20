<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class ContactUsEmail extends Mailable
{
    use Queueable, SerializesModels;
    protected  $userData;
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($userData)
    {
        $this->userData  =  $userData;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->view('emails.ContactUs') ->with([
            'subject' => $this->userData['subject'],
            'name'  =>  $this->userData['name'],
            'email' => $this->userData['email'],
            'mobile_no' => $this->userData['mobile_no'],
            'hear_about_us' => $this->userData['hear_about_us'],
            'msg' => $this->userData['message'],
        ]);
    }
}

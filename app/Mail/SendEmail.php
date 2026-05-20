<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class SendEmail extends Mailable {

    use Queueable,
        SerializesModels;

    public $data;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($data) {
        $this->data = $data;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build() {
        $address = env('MAIL_FROM_ADDRESS','info@edupme.com');
        $subject = $this->data['subject'];
        $name = env('APP_NAME', 'Knolzi');
        $view = 'emails.' . $this->data['template'];
        $headerData = [
            'category' => 'category',
            'unique_args' => [
                'variable_1' => 'abc'
            ]
        ];

        $header = $this->asString($headerData);

        $this->withSwiftMessage(function ($message) use ($header) {
            $message->getHeaders()
                    ->addTextHeader('X-SMTPAPI', $header);
        });

        return $this->view($view)
                        ->from($address, $name)
                        ->replyTo($address, $name)
                        ->subject($subject)
                        ->with([ 'html_body' => $this->data['html_body']]);
    }

    private function asJSON($data) {
        $json = json_encode($data);
        $json = preg_replace('/(["\]}])([,:])(["\[{])/', '$1$2 $3', $json);

        return $json;
    }

    private function asString($data) {
        $json = $this->asJSON($data);

        return wordwrap($json, 76, "\n   ");
    }

}

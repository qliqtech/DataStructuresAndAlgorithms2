<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class FlairEmail extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     *
     * @return void
     */

    protected $mailData;

    public function __construct($mailData)
    {


        $this->mailData = $mailData;

    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $address = $this->mailData->to;
        $subject = $this->mailData->subject;
     //   $name = $this->mailData->name;
     //   $message = $this->mailData->message;

        // $cc = $this->mailData->cc;
    //    $bcc = $this->mailData->bcc;
     //   $from = $this->mailData->from;
        return $this->view('emailflair')
            ->text('emailflairplaintext')
            ->from("info@myflair.africa", "Flair")
       //     ->cc($address, $name)
           // ->bcc($cc, $name)
           // ->replyTo("info@myflair.africa", $name)
            ->subject($subject)
            ->with(['mailMessage' => $this->mailData]);;


    }
}

<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class CarShareMail extends Mailable
{
    use Queueable, SerializesModels;

    public $lead;
    public $url;
    public $msg;

    public function __construct($lead, $url, $msg)
    {
        $this->lead = $lead;
        $this->url  = $url;
        $this->msg  = $msg;
    }

    public function build()
    {
        return $this->from(env('MAIL_FROM_ADDRESS'), env('MAIL_FROM_NAME', 'WedinWheels'))
            ->subject('WedinWheels | Select Your Car')
            ->view('emails.car_share')
            ->with([
                'lead' => $this->lead,
                'url'  => $this->url,
                'msg'  => $this->msg,
            ]);
    }
}

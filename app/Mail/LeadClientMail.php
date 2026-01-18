<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class LeadClientMail extends Mailable
{
    use Queueable, SerializesModels;

    public $lead;
    public $customMessage;

    public function __construct($lead, $customMessage = null)
    {
        $this->lead = $lead;
        $this->customMessage = $customMessage;
    }

    public function build()
    {
        return $this->from(env('MAIL_FROM_ADDRESS'), env('MAIL_FROM_NAME'))
            ->subject('Wedin Wheels - Booking Update')
            ->view('emails.lead_client_dark');
    }













    
}

<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class TestEmail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct() {}

    public function build()
    {
        return $this->from(env('MAIL_FROM_ADDRESS'), env('MAIL_FROM_NAME'))
            ->subject('Laravel SMTP Test - Wedin Wheels')
            ->html('
                <div style="font-family:Arial,sans-serif">
                    <h2>✅ SMTP Test Successful</h2>
                    <p>This is a test email sent from Laravel using Hostinger SMTP.</p>
                    <p><b>Time:</b> ' . now() . '</p>
                </div>
            ');
    }
}

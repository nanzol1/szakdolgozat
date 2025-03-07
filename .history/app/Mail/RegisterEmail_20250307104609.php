<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class RegisterEmail extends Mailable{
    use Queueable, SerializesModels;

    public $adat;
    public $email;
    public $subject;

    
}

?>
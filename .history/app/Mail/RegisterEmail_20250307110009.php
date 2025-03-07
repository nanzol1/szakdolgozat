<?php

namespace App\Mail;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class RegisterEmail extends Mailable{
    use Queueable, SerializesModels;

    public User $user;
    public $adat;
    public $email;
    public $subject;

    public function __construct(User $user){
        $this->user = $user;
    }

    public function build(){
        return $this->to($this->email)
                    ->subject($this->subject)
                    ->view('emails.emailtemplate');
    }
}

?>
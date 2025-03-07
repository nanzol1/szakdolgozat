<?php

namespace App\Mail;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class NewApplicantEmail extends Mailable{
    use Queueable, SerializesModels;

    public User $user;
    public $adat;
    public $email;
    public $subject;

    public function __construct($email,$subject,$adat){
        $this->email = $email;
        $this->subject = $subject;
        $this->adat = $adat;
    }

    public function build(){
        return $this->to($this->email)
                    ->subject($this->subject)
                    ->view('emails.emailtemplate');
    }
}

?>
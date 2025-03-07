<?php

namespace App\Listener;

use App\Events\NewApplicant;
use App\Mail\RegisterEmail;
use Illuminate\Support\Facades\Mail;

class SendNewApplicantEmail{
    public function handle(NewApplicant $event){
        $applicant = $event->apps;

        $email = $user->email;
        $subject = 'Üdvözlünk, '. $user->name . "!";
        $adat = [
            'nev' => $user->name,
        ];

        Mail::to($email)->send(new RegisterEmail($email,$subject,$adat));
    }
}

?>
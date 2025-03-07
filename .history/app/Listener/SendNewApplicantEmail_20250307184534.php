<?php

namespace App\Listener;

use App\Events\NewApplicant;
use App\Mail\RegisterEmail;
use Illuminate\Support\Facades\Mail;

class SendNewApplicantEmail{
    public function handle(NewApplicant $event){
        $applicant = $event->adv_job;

        $email = $applicant->companies->email;
        $subject = 'Üdvözlünk, '. $applicant->name . "!";
        $adat = [
            'nev' => $applicant->name,
        ];

        Mail::to($email)->send(new RegisterEmail($email,$subject,$adat));
    }
}

?>
<?php

namespace App\Listener;

use App\Events\NewApplicant;
use App\Mail\NewApplicantEmail;
use Illuminate\Support\Facades\Mail;

class SendNewApplicantEmail{
    public function handle(NewApplicant $event){
        $applicant = $event->adv_job;

        $email = $applicant->companies->email;
        $subject = 'Új jelentkező érkezett!';
        $adat = [
            'nev' => $applicant->name,
        ];

        Mail::to($email)->send(new NewApplicantEmail($email,$subject,$adat));
    }
}

?>
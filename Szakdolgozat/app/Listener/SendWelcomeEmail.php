<?php

namespace App\Listener;

use App\Events\UserRegistered;
use App\Mail\RegisterEmail;
use Illuminate\Support\Facades\Mail;

class SendWelcomeEmail{
    public function handle(UserRegistered $event){
        $user = $event->user;

        $email = $user->email;
        $subject = 'Üdvözlünk, '. $user->name . "!";
        $adat = [
            'nev' => $user->name,
        ];

        Mail::to($email)->send(new RegisterEmail($email,$subject,$adat));
    }
}

?>
<?php

namespace App\Events;

use App\Models\User;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\SerializesModels;

class NewApplicant{
    use Dispatchable, SerializesModels;

    public $user;

    public function __construct(User $user){
        $this->user = $user;
    }
}

?>
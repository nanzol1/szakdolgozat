<?php

namespace App\Events;

use App\Models\Application;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\SerializesModels;

class NewApplicant{
    use Dispatchable, SerializesModels;

    public $applicant;

    public function __construct(Application $applicant){
        $this->user = $applicant;
    }
}

?>
<?php

namespace App\Events;

use App\Models\JobVacancy;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\SerializesModels;

class NewApplicant{
    use Dispatchable, SerializesModels;

    public $adv_job;

    public function __construct(JobVacancy $adv_job){
        $this->adv_job = 
    }
}

?>
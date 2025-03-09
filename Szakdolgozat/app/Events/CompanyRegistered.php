<?php

namespace App\Events;

use App\Models\Company;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\SerializesModels;

class CompanyRegistered{
    use Dispatchable, SerializesModels;

    public $company;

    public function __construct(Company $company){
        $this->company = $company;
    }
}

?>
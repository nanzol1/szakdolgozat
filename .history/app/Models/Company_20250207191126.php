<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;


class Company extends Authenticatable{
    use Notifiable;
    protected $table = "companies";

    protected $fillable = ["company_name","email","password","company_pnumber","company_desc","company_address","job_vacancies_id",'profile_pict'];

    protected $hidden = ["password"];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

}

?>
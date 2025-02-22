<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Notifications\Notifiable;


class Company extends Authenticatable{
    use Notifiable,HasFactory;
    protected $table = "companies";

    protected $fillable = [
        "company_name","email","password","company_pnumber","company_desc","company_address","job_vacancies_id",'profile_pict','is_active'];

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
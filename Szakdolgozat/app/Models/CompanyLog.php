<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CompanyLog extends Model
{
    use HasFactory;

    protected $table = "company_logs";
    protected $fillable = ['action', 'description','company_id'];
}


?>
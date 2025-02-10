<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model; 

class Education extends Model{

    protected $table = "educations";

    protected $fillable = ['education_type','instutition_name','education_name','date_from','date_to','education_place','user_id'];

    protected $casts = [
        'date_from' => 'date',
        'date_to' => 'date',
    ];

    protected $hidden = [
        'user_id',
    ];
}
?>
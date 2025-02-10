<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model; 

class Education extends Model{

    protected $table = "educations";

    protected $fillable = ['education_type','instutition_name','education_name','date_from',]
}
?>
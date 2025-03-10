<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\belongsTo;

class JobSubCategory extends Model{
    protected $table = "jobs_subcategories";

    protected $fillable = ['name','maincateg_id'];

    public function jobs_categories(): belongsTo{ 
        return $this->belongsTo(JobCategory::class,'maincateg_id');
    }
}

?>
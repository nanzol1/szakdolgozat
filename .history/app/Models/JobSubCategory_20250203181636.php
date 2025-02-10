<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;

class JobSubCategory extends Model{
    protected $table = "jobs_subcategories";

    protected $fillable = ['name','maincateg_id'];

    public function jobs_categories(): HasOne{ 
        return $this->hasOne(JobCategory::class);
    }
}

?>
<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class JobSubCategory extends Model{
    protected $table = "jobs_subcategories";

    protected $fillable = ['name'];

    public function jobs_categories(): BelongsTo{ 
        return $this->belongsTo(JobCategory::class,'id','name');
    }
}

?>
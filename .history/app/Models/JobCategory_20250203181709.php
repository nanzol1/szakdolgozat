<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;


class JobCategory extends Model{
    protected $table = "jobs_categories";

    protected $fillable = ['name'];

    public function jobs_subcategories(){
        return $this->hasMany(JobSubCategory::class);
    }
}

?>
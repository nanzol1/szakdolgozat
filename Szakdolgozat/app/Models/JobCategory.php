<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;


class JobCategory extends Model{
    protected $table = "jobs_categories";

    protected $fillable = ['name'];

    public function jobs_subcategories(): HasMany{
        return $this->hasMany(JobSubCategory::class,'maincateg_id');
    }
}

?>
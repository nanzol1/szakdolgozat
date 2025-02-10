<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
class JobVacancy extends Model{
    protected $table = "job_vacancies";

    protected $fillable = ['name','position','description','requirements','payment','created_by'];
    protected function casts(): array{
        return [
            'position' => 'array',
        ];
    }

    public function jobs_category(){
        return $this->hasMany(JobCategory::class);
    }

    public function jobs_subcategories(){
        return $this->hasMany(JobSubCategory::class);
    }
}

?>
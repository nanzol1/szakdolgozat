<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
class JobVacancy extends Model{
    protected $table = "job_vacancies";

    protected $fillable = ['name','position','description','requirements','payment','created_by','maincateg_id','subcateg_id'];
    protected function casts(): array{
        return [
            'position' => 'array',
        ];
    }

    public function jobs_category(){
        return $this->belongsTo(JobCategory::class,'maincateg_id');
    }

    public function jobs_subcategory(){
        return $this->belongsTo(JobSubCategory::class,'subcateg_id');
    }

    public function users(){
        return $this->belongsTo(User::class,'created_by');
    }
}

?>
<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
class JobVacancy extends Model{
    protected $table = "job_vacancies";

    protected $fillable = ['name','address','active','position','description','requirements','payment','created_by','maincateg_id','subcateg_id','employment_type','work_schedules'];
    protected function casts(): array{
        return [
            'position' => 'array',
            'employment_type' => 'array',
            'work_schedules' => 'array',
        ];
    }

    public function jobs_category(){
        return $this->belongsTo(JobCategory::class,'maincateg_id');
    }

    public function jobs_subcategory(){
        return $this->belongsTo(JobSubCategory::class,'subcateg_id');
    }

    public function companies(){
        return $this->belongsTo(Company::class,'created_by');
    }

    public function employment_type(){
        return $this->hasMany(Employment_type::class,'employment_type');
    }

    public function work_schedules(){
        return $this->belongsTo(Work_schedule::class,'id','work_schedules');
    }
}

?>
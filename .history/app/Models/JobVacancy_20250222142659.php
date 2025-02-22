<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class JobVacancy extends Model{
    use HasFactory;
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
        return $this->belongsToMany(Employment_type::class,'advertised_jobs_employment_type','job_vacancy_id','employment_type_id');
    }

    public function work_schedules(){
        return $this->belongsToMany(Work_schedule::class,'advertised_jobs_work_schedules','advertised_job_id','work_schedules_id');
    }

    public function applicants(){
        return $this->hasMany(Application::class,'job_id');
    }
}

?>
<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Employment_type extends Model{
    protected $table = "employment_type";

    protected $fillable = ["name"];

    public function jobVacancies()
    {
        return $this->belongsToMany(JobVacancy::class, 'job_vacancy_employment_type', 'employment_type_id', 'job_vacancy_id');
    }
}
?>
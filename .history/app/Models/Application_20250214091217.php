<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
class Application extends Model{
    protected $table = "applications";

    protected $fillable = ['job_id','user_id','status','is_deleted'];

    public function user(){
        return $this->belongsTo(User::class,'user_id');
    }

    public function job_vacancy(){
        return $this->belongsTo(JobVacancy::class,'job_id');
    }

    public function job_status(){
        return $this->belongsTo(JobStatus::class,'status');
    }

    public function company_info(){
        return $this->belongsTo(Company::class,'');
    }
}
?>
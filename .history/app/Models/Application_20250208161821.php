<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
class Application extends Model{
    protected $table = "applications";

    protected $fillable = ['job_id','user_id','status'];

    public function user(){
        return $this->belongsTo(User::class);
    }

    public function job_vacancy(){
        return $this->belongsTo(JobVacancy::class);
    }

    public function job_status(){
        return $this->belongsTo(JobStatus::class,'status');
    }
}
?>
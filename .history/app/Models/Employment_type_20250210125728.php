<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Employment_type extends Model{
    protected $table = "employment_type";

    protected $fillable = ["name"];

    public function jobs_advertised(){
        return $this->hasMany(JobVacancy::class,"","id");
    }
}
?>
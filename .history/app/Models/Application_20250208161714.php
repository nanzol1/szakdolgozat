<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
class Application extends Model{
    protected $table = "applications";

    protected $fillable = ['job_id','user_id','status'];
    
}
?>
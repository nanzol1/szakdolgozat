<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Application_notification extends Model{
    protected $table = "applications_notifications";

    protected $fillable = ['user_id','message','is_read'];

}
?>
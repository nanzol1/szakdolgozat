<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Prepared_CV extends Model{

    protected $table = "prepared_cv";

    protected $fillable = ['firstname','lastname','phone','email','county','city','address','zip','exps','educations','skills','more_desc','plain_text'];

    protected function casts(): array
    {
        return [
            'exps' => 'array',
            'educations' => 'array',
            'skills' => 'array',
        ];
    }
}
?>
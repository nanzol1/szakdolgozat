<?php
namespace App\Http\Controllers;

class JobsController extends Controller{
    public function index(){
        if(auth()->guard('web')->check() || auth()->guard('company')->check()){
            
        }
    }
}
?>
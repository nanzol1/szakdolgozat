<?php
namespace App\Http\Controllers;
use Inertia\Inertia;
class JobsController extends Controller{
    public function index(){
        if(auth()->guard('web')->check() || auth()->guard('company')->check()){
            return Inertia::render('Jobs/Jobslist',[
                'jobs' => 
            ]);
        }

        return redirect('/login');
    }
}
?>
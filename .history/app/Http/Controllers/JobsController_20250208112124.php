<?php
namespace App\Http\Controllers;
use App\Models\JobVacancy;
use Inertia\Inertia;
use App\Model\Job;
class JobsController extends Controller{
    public function index(){
        if(auth()->guard('web')->check() || auth()->guard('company')->check()){
            $jobs = JobVacancy::all();
            return Inertia::render('Jobs/Jobslist',[
                'jobs' => $jobs
            ]);
        }

        return redirect('/login');
    }
}
?>
<?php
namespace App\Http\Controllers;
use App\Models\JobVacancy;
use Inertia\Inertia;
class JobsController extends Controller{
    public function index(){
        if(auth()->guard('web')->check() || auth()->guard('company')->check()){
            $jobs = JobVacancy::with('jobs_category','jobs_subcategory')->get();
            foreach($jobs as $item){
                dd($item);
            }
            dd($jobs);
            return Inertia::render('Jobs/Jobslist',[
                'jobs' => $jobs
            ]);
        }

        return redirect('/login');
    }
}
?>
<?php
namespace App\Http\Controllers;
use App\Models\JobVacancy;
use Inertia\Inertia;
class JobsController extends Controller{
    public function index(){
        if(auth()->guard('web')->check() || auth()->guard('company')->check()){
            $jobs = JobVacancy::with('jobs_category','jobs_subcategory')->get();
            $cleanedJobs = [];
            foreach($jobs as $item){
                dd($item->jobs_category());
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
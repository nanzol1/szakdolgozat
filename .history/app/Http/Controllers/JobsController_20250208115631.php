<?php
namespace App\Http\Controllers;
use App\Models\JobVacancy;
use Inertia\Inertia;
class JobsController extends Controller{
    public function index(){
        if(auth()->guard('web')->check() || auth()->guard('company')->check()){
            $jobs = JobVacancy::with('jobs_category','jobs_subcategory','user')->get();
            $cleanedJobs = [];
            foreach($jobs as $item){
                $cleanedJobs[$item->id] = [
                    'id'=> $item->id,
                    'name'=> $item->name,
                    'created_by' => 
                ];
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
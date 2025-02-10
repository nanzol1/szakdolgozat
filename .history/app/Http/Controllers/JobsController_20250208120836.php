<?php
namespace App\Http\Controllers;
use App\Models\JobVacancy;
use Inertia\Inertia;
use Illuminate\Http\Request;
class JobsController extends Controller{
    public function index(){
        if(auth()->guard('web')->check() || auth()->guard('company')->check()){
            $jobs = JobVacancy::with('jobs_category','jobs_subcategory','companies')->get();
            $cleanedJobs = [];
            foreach($jobs as $item){
                $cleanedJobs[$item->id] = [
                    'id'=> $item->id,
                    'name'=> $item->name,
                    'position_name' => $item->jobs_subcategory->name,
                    'description' => $item->description,
                    'created_by' => $item->companies->company_name,
                    'maincateg_id' => $item->jobs_category->id,
                    'sucateg_id' => $item->jobs_subcategory->id,
                ];
            }
            return Inertia::render('Jobs/Jobslist',[
                'jobs' => $cleanedJobs
            ]);
        }

        return redirect('/login');
    }

    public function jobView(Request $request, JobVacancy $jobVacancy){
        if(auth()->guard('web')->check() || auth()->guard('company')->check()){
            $jobVacancy->get();
            return Inertia::render('Jobs/Jobsview',[
                'job'=>$jobVacancy,
            ]);
        }
    }
}
?>
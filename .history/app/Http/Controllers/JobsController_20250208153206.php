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

    public function jobView($id){
        if(auth()->guard('web')->check() || auth()->guard('company')->check()){
            $jobVacancy = JobVacancy::with('jobs_category','jobs_subcategory','companies')->where('id','=',$id)->first();
            $cleanedData = [
                'id' => $jobVacancy->id,
                'name'=> $jobVacancy->name,
                'description' => $jobVacancy->description,
                'company_name'=> $jobVacancy->companies->company_name,
                'requirements' => $jobVacancy->requirements,
                'payment' => $jobVacancy->payment,
                'maincateg_name' => $jobVacancy->jobs_category->name,
                'subcateg_name' => $jobVacancy->jobs_subcategory->name,
            ];
            return Inertia::render('Jobs/Jobsview',[
                'job'=>$cleanedData,
            ]);
        }
    }

    public function applyForJob(Request $request){
        if(auth()->guard('web')->check() || auth()->guard('company')->check()){
            if($request->job_id){
                $appliedFor = json_decode($request->user()->applied_for,true) ?? [];
                $newApply = $request->job_id;
                if(!in_array($newApply,$appliedFor)){
                    $appliedFor[] = $newApply;
                }
                $request->user()->applied_for = json_encode($appliedFor);
                $request->user()->save();
            }
        }
    }

    public function revokeApplication(Request $request){
        if(auth()->guard('web')->check() || auth()->guard('company')->check()){
            if($request->job_id){
                $appliedFor = json_decode($request->user()->applied_for,true) ?? [];
                $newApply = $request->job_id;
                dd(in_array($newApply,$appliedFor));
                foreach($appliedFor as $item){
                    if(in_array($newApply,$appliedFor)){
                        dd($item);
                    }
                }
                $request->user()->applied_for = json_encode($appliedFor);
                $request->user()->save();
            }
        }
    }
    
}
?>
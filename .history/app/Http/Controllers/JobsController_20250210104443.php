<?php
namespace App\Http\Controllers;
use App\Models\Application;
use App\Models\JobSubCategory;
use App\Models\JobVacancy;
use App\Models\JobCategory;
use Inertia\Inertia;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
class JobsController extends Controller{
    public function index(Request $request){
        if(auth()->guard('web')->check() || auth()->guard('company')->check()){
            $query = JobVacancy::with('jobs_category','jobs_subcategory','companies');
            if($request->filled('search')){
                $query->where('name','LIKE','%'.$request->search.'%');
            }
            if($request->filled('category')){
                $query->where('maincateg_id',$request->category);
            }
            if($request->filled('subcategory')){
                $query->where('subcateg_id',$request->subcategory);
            }
            $jobs = $query->get();
            $categories = JobCategory::with('jobs_subcategories')->get();
            $cleanedCats = [];
            foreach($categories as $cats){
                $cleanedCats[$cats->id] = [
                    'id' => $cats->id,
                    'name' => $cats->name,
                    'subcategories_id' => $cats->jobs_subcategories->id,
                    'subcategories_name' => $cats->jobs_subcategories->name,
                ];
                dd($cats->jobs_subcategories);
            }
            dd($cleanedCats);
            $cleanedJobs = [];
            foreach($jobs as $item){
                $cleanedJobs[$item->id] = [
                    'id'=> $item->id,
                    'name'=> $item->name,
                    'active' => $item->active,
                    'position_name' => $item->jobs_subcategory->name,
                    'description' => $item->description,
                    'created_by' => $item->companies->company_name,
                    'maincateg_id' => $item->jobs_category->id,
                    'sucateg_id' => $item->jobs_subcategory->id,
                ];
            }
            return Inertia::render('Jobs/Jobslist',[
                'jobs' => $cleanedJobs,
                'categories' => $categories,
                'filters' => $request->only(['search','category','subcategory']),
            ]);
        }

        return redirect('/login');
    }

    public function jobView($id){
        if(auth()->guard('web')->check() || auth()->guard('company')->check()){
            $jobVacancy = JobVacancy::with('jobs_category','jobs_subcategory','companies')->where('id','=',$id)->first();
            $applications = Application::where('user_id','=',request()->user()->id)->where('job_id','=',$id)->get();
            $cleanedData = [
                'id' => $jobVacancy->id,
                'name'=> $jobVacancy->name,
                'active' => $jobVacancy->active,
                'description' => $jobVacancy->description,
                'company_name'=> $jobVacancy->companies->company_name,
                'requirements' => $jobVacancy->requirements,
                'payment' => $jobVacancy->payment,
                'maincateg_name' => $jobVacancy->jobs_category->name,
                'subcateg_name' => $jobVacancy->jobs_subcategory->name,
            ];
            return Inertia::render('Jobs/Jobsview',[
                'job'=>$cleanedData,
                'application' => $applications,
            ]);
        }
    }

    public function applyForJob(Request $request){
        if(auth()->guard('web')->check() || auth()->guard('company')->check()){
            if($request->job_id){
                $job_id = $request->job_id;
                $user_id = $request->user()->id;
                $datas = [
                    'job_id' => $job_id,
                    'user_id'=> $user_id,
                    'status' => 1,
                    'is_deleted' => 0,
                ];

                $validate = Validator::make($datas, [
                    'job_id' => 'Integer',
                    'user_id' => 'Integer',
                    'status' => 'Integer',
                    'is_deleted' => 'Integer',
                ])->validate();

                //Ellenőrzőm hogy van-e már létrehozott rekord a usernek
                $findJob = Application::where('job_id','=',$job_id)
                ->where('user_id','=',$user_id)
                ->where('is_deleted','=',1)->first();

                if($findJob){
                    $findJob->update(['is_deleted' => 0]);
                }else{
                    Application::create($validate);
                }

            }
        }
    }

    public function revokeApplication(Request $request){
        if(auth()->guard('web')->check() || auth()->guard('company')->check()){
            if($request->job_id){
                $findJob = Application::where('job_id','=',$request->job_id)
                ->where('user_id','=',$request->user()->id)
                ->first();
                
                if($findJob){
                    $findJob->update(['is_deleted' => 1]);
                }
            }
        }
    }
    
}
?>
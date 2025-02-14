<?php
namespace App\Http\Controllers;
use App\Models\Application;
use App\Models\JobCategory;
use App\Models\Employment_type;
use App\Models\Work_schedule;
use App\Models\JobVacancy;
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
                $query->whereIn('maincateg_id',$request->category);
            }
            if($request->filled('subcategory')){
                $query->whereIn('subcateg_id',$request->subcategory);
            }
            $jobs = $query->get();
            $categories = JobCategory::with('jobs_subcategories')->get();
            $cleanedCats = [];
            foreach($categories as $cats){
                $cleanedCats[$cats->id] = [
                    'mid' => $cats->id,
                    'name' => $cats->name,
                    'subcategories' => [],
                ];
                foreach($cats->jobs_subcategories as $subs){
                    $cleanedCats[$cats->id]['subcategories'][] = [
                        'subcat_id' => $subs->id,
                        'subcat_name' => $subs->name,
                        'subcat_keyid' => $subs->key_id,
                    ];
                }
            }
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
                'categories' => $cleanedCats,
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

    public function getMyJobs(){
        $applications = Application::with([
            'job_vacancy' => function($query){
                $query->select('id','created_by','active','name','address','description','requirements','maincateg_id','subcateg_id');
            },
            'job_vacancy.companies' => function($query){
                $query->select('id','company_name','profile_pict');
            },
            'job_vacancy.jobs_category' => function($query){
                $query->select('id','name','key_id');
            },
            'job_vacancy.jobs_subcategory' => function($query){
                $query->select('id','name','key_id');
            },
            'job_status'])
            ->where('user_id','=',auth()->user()->id)
            ->where('is_deleted','!=','1')
            ->get()
            ->toArray();
        $jobs = array_map(function($app) {
            return [
                'job_name' => $app['job_vacancy']['name'],
                'job_address' => $app['job_vacancy']['address'],
                'job_description' => $app['job_vacancy']['description'],
                'job_requirements' => $app['job_vacancy']['requirements'],
                'job_cname' => $app['job_vacancy']['companies']['company_name'],
                'job_cpict' => $app['job_vacancy']['companies']['profile_pict'],
                'job_catname' => $app['job_vacancy']['jobs_category']['name'],
                'job_scatname' => $app['job_vacancy']['jobs_subcategory']['name'],
                'job_status_name' => $app['job_status']['name'],
            ];
        },$applications);

        return Inertia::render('Profile/UserProfile/Jobs/MyJobs',[
            'myjobs' => $jobs,
        ]);
    }

    public function getListedJobs(){
        $listedJobs = JobVacancy::with(['employment_type','work_schedules','jobs_category','jobs_subcategory',])
        ->select('id','active','name','description','created_by','maincateg_id','subcateg_id')
        ->where('created_by','=',auth()->user()->id)
        ->get()
        ->toArray();
        return Inertia::render('Profile/CompanyProfile/Jobs/ListedJobs',[
            'listedJobs' => $listedJobs,
        ]);
    }

    public function editListedJob(Request $request){
        $job = JobVacancy::find($request->id);
        $category = JobCategory::with('jobs_subcategories')->get();
        $categories = [];
        $employment_types = Employment_type::all();
        $work_schedules = Work_schedule::all();
        foreach($category as $item){
            $categories[$item->id] = [
                'id' => $item->id,
                'name'=> $item->name,
                'subcategories' => $item->jobs_subcategories->toArray() ?? [],
            ];
        }
        return Inertia::render('Profile/CompanyProfile/Jobs/EditJob',[
            'job' => $job,
            'categories' => $categories,
            'employment_types' => $employment_types,
            'work_schedules' => $work_schedules,
        ]);
    }

    public function saveListedJob(Request $request){
        $datas = $request->all();
        if($datas['zip_address'] && $datas['county_address'] && $datas['place_address']){
            $datas['address'] = $datas['zip_address'].','.$datas['county_address'].','.$datas['place_address'];
        }

        unset($datas['zip_address']);
        unset($datas['county_address']);
        unset($datas['place_address']);

        if($datas){
            $validated = Validator::make($datas,[
                'name' => 'String|required|min:3|max:255',
                'active' => 'Integer',
                'position' => 'array|required',
                'description' => 'String|required|min:3|max:255',
                'requirements' => 'String|required|min:3|max:255',
                'payment' => 'nullable|String|min:3|max:255',
                'created_by' => 'Integer',
                'maincateg_id' => 'Integer',
                'subcateg_id' => 'Integer',
                'employment_type' => 'array|required',
                'work_schedules' => 'array|required',
                'address' => 'String|required',
            ])->validate();
            dd($validated);

        }
    }
    
}
?>
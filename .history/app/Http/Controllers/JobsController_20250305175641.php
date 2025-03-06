<?php
namespace App\Http\Controllers;
use App\Models\Application;
use App\Models\Application_notification;
use App\Models\JobCategory;
use App\Models\Employment_type;
use App\Models\JobStatus;
use App\Models\Work_schedule;
use App\Models\JobVacancy;
use Inertia\Inertia;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Facades\LogFacade as Log;
use Route;


class JobsController extends Controller{
    public function index(Request $request){
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
        $work_schedules = Work_schedule::all();
        $employment_type = Employment_type::all();
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
                    'subcat_maincid' => $subs->maincateg_id,
                ];
            }
        }
        if($request->filled('min_payment') && $request->filled('min_payment')){
            $minPayment = (int) $request->input('min_payment');
            $maxPayment = (int) $request->input('max_payment');
        
            if(is_int($minPayment) && is_int($maxPayment) && $minPayment <= $maxPayment){
                $query->whereBetween('payment',[$minPayment,$maxPayment]);
            }
        }
        $min_payment = min(JobVacancy::select('payment')->where('payment','!=',null)->get()->toArray());
        $max_payment = max(JobVacancy::select('payment')->where('payment','!=',null)->get()->toArray());
        $workSchedules = $request->input('work_schedules');

        if($request->filled('work_schedules')){
            foreach ($workSchedules as $schedule) {
                $query->where('work_schedules','LIKE', '%'.$schedule.'%');
            }
        }
        $employmentType = $request->input('employment_type');

        if($request->filled('employment_type')){
            foreach ($employmentType as $employment) {
                $query->where('employment_type','LIKE', '%'.$employment.'%');
            }
        }
        $jobs = $query->paginate(6);
        return Inertia::render('Jobs/Jobslist',[
            'jobs' => $jobs,
            'categories' => $cleanedCats,
            'filters' => $request->only(['search','category','subcategory','min_payment','max_payment']),
            'min_spayment' => $min_payment,
            'max_spayment' => $max_payment,
            'work_schedules' => $work_schedules,
            'employment_type' => $employment_type,
        ]);
    }

    public function jobView($id){
        $jobVacancy = JobVacancy::with('jobs_category','jobs_subcategory','companies',
        'work_schedules','employment_type')->where('id','=',$id)->first();
        $jobs = JobVacancy::limit(8)->get();
        if(auth()->guard('web')->check()){
            $applications = Application::where('user_id','=',request()->user()->id)->where('job_id','=',$id)->get();
        }
        return Inertia::render('Jobs/Jobsview',[
            'job'=>$jobVacancy,
            'jobs'=> $jobs,
            'application' => $applications ?? [],
        ]);
    }

    public function applyForJob(Request $request){
        if(auth()->guard('web')->check()){
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

                $created_by = JobVacancy::where('id','=', $job_id)->first();

                //Ellenőrzőm hogy van-e már létrehozott rekord a usernek
                $findJob = Application::where('job_id','=',$job_id)
                ->where('user_id','=',$user_id)
                ->where('is_deleted','=',1)->first();
                if($findJob){
                    $findJob->update(['is_deleted' => 0]);
                }else{
                    Application_notification::create([
                        'user_id' => $request->user()->id,
                        'job_id' => $validate['job_id'],
                        'message' => 'Új jelentkező érkezett',
                        'is_read' => 0,
                        'created_by' => $created_by['created_by'],
                    ]);
                    Application::create($validate);
                }
                Log::logUserAction('jelentkezes','Jelentkezett erre a munkára -> ('.$validate['job_id'].')',auth('web')->user()->id);
                return redirect()->back()->with('success','Sikeres jelentkezés!');
            }
        }
    }

    public function revokeApplication(Request $request){
        if(auth()->guard('web')->check()){
            if($request->job_id){
                $findJob = Application::where('job_id','=',$request->job_id)
                ->where('user_id','=',$request->user()->id)
                ->first();
                
                if($findJob){
                    $findJob->update(['is_deleted' => 1]);
                }
            }
            Log::logUserAction('lejelentkezes','Visszavonta a jelentkezést erről a munkáról -> ('.$request->job_id.')',auth('web')->user()->id);
        }
    }

    public function getMyJobs(){
        $applications = Application::with([
            'job_vacancy' => function($query){
                $query->select('id','created_by','active','name','address','description','requirements','maincateg_id','subcateg_id','payment','work_schedules')
                ->with(['schedules','employments_type']);
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
            'job_vacancy.employment_type',
            'job_vacancy.work_schedules',
            'job_status'])
            ->where('user_id','=',auth()->user()->id)
            ->where('is_deleted','!=','1')
            ->paginate(5);
        $jobs = $applications->getCollection()->map(function($app) {
            return [
                'job_id' => $app['job_vacancy']['id'],
                'job_name' => $app['job_vacancy']['name'] ?? '',
                'job_active' => $app['job_vacancy']['active'] ? 'Aktív' : 'Inaktív',
                'job_address' => $app['job_vacancy']['address'] ?? '',
                'job_description' => $app['job_vacancy']['description'] ?? '',
                'job_requirements' => $app['job_vacancy']['requirements'] ?? '',
                'job_cname' => $app['job_vacancy']['companies']['company_name'] ?? '',
                'job_cpict' => $app['job_vacancy']['companies']['profile_pict'] ?? '',
                'job_catname' => $app['job_vacancy']['jobs_category']['name'] ?? '',
                'job_scatname' => $app['job_vacancy']['jobs_subcategory']['name'] ?? '',
                'job_status_name' => $app['job_status']['name'] ?? '',
                'job_payment' => $app['job_vacancy']['payment'] ?? 0,
                'work_schedules' => $app['job_vacancy']['schedules'] ?? [],
                'employment_types' => $app['job_vacancy']['employments_type'] ?? [],
            ];
        });
        $applications->setCollection($jobs);
        $work_schedules = Work_schedule::all();
        $employment_type = Employment_type::all();
        return Inertia::render('Profile/UserProfile/Jobs/MyJobs',[
            'myjobs' => $applications,
            'work_schedules' => $work_schedules,
            'employment_type' => $employment_type,
        ]);
    }

    public function getListedJobs(){
        $listedJobs = JobVacancy::with(['employment_type','work_schedules','jobs_category','jobs_subcategory',])
        ->select('id','active','name','description','created_by','maincateg_id','subcateg_id')
        ->where('created_by','=',auth()->user()->id)
        ->paginate(6);
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
        $applications = Application::with(['user' => function($query){
            $query->select('id','name','email','phone_number');
        },
        'job_status'])->where('job_id','=',$request->id)->where('is_deleted','!=','1')
        ->get()->toArray();
        $statuses = JobStatus::all();
        $cleanedApps = array_map(function($apps) {
            return [
                'id' => $apps['id'],
                'status_name' => $apps['job_status']['name'],
                'job_id' => $apps['job_id'],
                'user_id' => $apps['user']['id'],
                'name' => $apps['user']['name'],
                'email' => $apps['user']['email'],
                'phone' => $apps['user']['phone_number'],
            ];
        },$applications);
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
            'applications' => $cleanedApps,
            'statuses' => $statuses,
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
                'description' => 'String|required|min:3',
                'requirements' => 'String|required|min:3',
                'payment' => 'nullable|integer',
                'created_by' => 'Integer',
                'maincateg_id' => 'Integer',
                'subcateg_id' => 'Integer',
                'employment_type' => 'array|required',
                'work_schedules' => 'array|required',
                'address' => 'String|required',
            ],[
                'description.*' => 'Kötelező mező!',
                'requirements.*' => 'Kötelező mező!',
            ])->validate();
            
            $job = JobVacancy::find($request->id);

            Log::logCompanyAction('update','Frissítette a munka adatait -> ('.$request->id.')',auth('company')->user()->id);
            if($job){
                $job->update($validated);
                if(isset($validated['employment_type'])){
                    $job->employment_type()->sync($validated['employment_type']);
                }

                if(isset($validated['work_schedules'])){
                    $job->work_schedules()->sync($validated['work_schedules']);
                }
            }
            return back()->with('success','Sikeresen el lett mentve!');
        }
    }

    public function showApplicant($jobId, $applicantId){
        $applications = Application::with(['user' => function($query){
            $query->select('id','name','email','phone_number');
        },
        'job_status'])->where('job_id','=',$jobId)->where('is_deleted','!=','1')
        ->where('user_id','=',$applicantId)
        ->get()->toArray();
        $cleanedApps = array_map(function($apps) {
            return [
                'id' => $apps['id'],
                'status_name' => $apps['job_status']['name'],
                'status_id' => $apps['status'],
                'job_id' => $apps['job_id'],
                'user_id' => $apps['user']['id'],
                'name' => $apps['user']['name'],
                'email' => $apps['user']['email'],
                'phone' => $apps['user']['phone_number'],
            ];
        },$applications);

        return response()->json([
            'applicant' => $cleanedApps[0] ?? null,
        ]);
    }

    public function updateStatus($jobId,$applicantId, $status){
        $applicant = Application::where('job_id','=',$jobId)
        ->where('user_id','=',$applicantId)->first();
        if($applicant){
            $applicant->update(['status' => $status]);
        }

        Log::logCompanyAction('update','Frissítette a jelentkező státuszát -> ('.$applicant['id'].'). Erre a státuszra -> ('.$applicant['status'].')',auth('company')->user()->id);

        return back()->with('status',$applicant['status']);
    }
    
}
?>
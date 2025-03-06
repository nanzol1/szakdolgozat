<?php

namespace App\Http\Controllers;

use App\Models\Application;
use App\Models\Employment_type;
use App\Models\JobVacancy;
use App\Models\Work_schedule;
use Inertia\Inertia;
use Illuminate\Http\Request;

class DashboardController extends Controller{
    public function index(){
        $applications = Application::with([
            'job_vacancy' => function($query){
                $query->select('id','created_by','active','name','address','description','requirements','maincateg_id','subcateg_id','payment');
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
            ->paginate(5);
        $jobs = $applications->getCollection()->map(function($app) {
            return [
                'job_id' => $app['id'],
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
        return Inertia::render('Dashboard',[
            'myjobs' => $applications,
        ]);
    }

    public function getAppliedJobsTable(Request $request)
    {

        $request->validate([
            'sortBy' => 'sometimes|string',
            'sortDesc' => 'sometimes|in:asc,desc'
        ]);
        $applications = Application::query()
        ->select([
            'applications.id as application_id',
            'applications.user_id',
            'applications.job_id',
            'applications.status',
            'job_vacancies.id as job_vacancy_id',
            'job_vacancies.name as job_name',
            'job_vacancies.active as job_active',
            'jobs_subcategories.id as subcategory_id',
            'jobs_subcategories.name as subcategory_name',
            'job_statuses.id as status_id',
            'job_statuses.name as status_name'
        ])
        ->with([
            'job_vacancy' => function($query) {
                $query->select('id','created_by','active','name','address','description','requirements','maincateg_id','subcateg_id','payment')
                ->with(['schedules','employments_type']);
            },
            'job_vacancy.companies',
            'job_status',
            'job_vacancy.jobs_subcategory' => function($query){
                $query->select('id','name','key_id');
            },
        ])
        ->join('job_vacancies', 'job_vacancies.id', '=', 'applications.job_id')
        ->join('jobs_subcategories', 'jobs_subcategories.id', '=', 'job_vacancies.subcateg_id')
        ->join('job_statuses','job_statuses.id','=','applications.status')
        ->where('user_id', auth()->id())
        ->where('is_deleted', '!=', 1);
        if ($request->has('sortBy')) {
            $sortDirection = $request->sortDesc === 'desc' ? 'desc' : 'asc';

            switch ($request->sortBy) {
                case 'name':
                    $applications->orderBy('job_name', $sortDirection);
                    break;
        
                case 'subcat':
                    $applications->orderBy('subcategory_name', $sortDirection);
                    break;
        
                case 'status':
                    $applications->orderBy('status_name', $sortDirection);
                    break;
        
                case 'active':
                    $applications->orderBy('job_active', $sortDirection);
                    break;
        
                default:
                    $applications->orderBy('applications.created_at', $sortDirection);
            }
        }
        $paginator = $applications->paginate(5);
        $transformed = $paginator->getCollection()->map(function ($app) {
            return [
                'job_id' => $app->job_id,
                'job_name' => $app->job_vacancy->name ?? 'N/A',
                'job_cname' => $app->job_vacancy->companies->company_name,
                'job_status_name' => $app->job_status->name ?? 'N/A',
                'job_active' => $app->job_vacancy->active ? 'Aktív' : 'Inaktív',
                'job_address' => $app->job_vacancy->address ?? '',
                'job_description' => $app->job_vacancy->description ?? '',
                'job_requirements' => $app->job_vacancy->requirements ?? '',
                'job_cpict' => $app->job_vacancy->companies->profile_pict ?? '',
                'job_catname' => $app->job_vacancy->jobs_category->name ?? '',
                'job_scatname' => $app->job_vacancy->jobs_subcategory->name ?? '',
                'job_payment' => $app->job_vacancy->payment ?? 0,
                'work_schedules' => $app->job_vacancy->schedules,
                'employment_types' => $app->job_vacancy->employments_type,

            ];
        });
        $paginator->setCollection($transformed);

        return response()->json(['status' => 'success', 'list'=> $paginator]);
    }

    public function companyIndex(Request $request){
        $myjobs = JobVacancy::where('created_by','=',$request->user()->id)
        ->with('jobs_category','jobs_subcategory','schedules','employments_type')
        ->paginate(5);
        $jobs_stat = JobVacancy::where('created_by','=',$request->user()->id)
        ->with('applicants')->get()->toArray();
        $jobsCount = array_filter($jobs_stat, function($fun) {
            return count($fun);
        });
        dd($jobsCount);
        return Inertia::render('CDashboard',[
            'myjobs' => $myjobs,
            'jobs_count' => $jobsCount,
        ]);
    }

    public function getAdvertisedJobsTable(Request $request)
    {

        $request->validate([
            'sortBy' => 'sometimes|string',
            'sortDesc' => 'sometimes|in:asc,desc'
        ]);
        $advertised = JobVacancy::query();
        $advertised->with('jobs_category','jobs_subcategory','schedules','employments_type');
        $advertised->where('created_by','=',$request->user()->id);
        if ($request->has('sortBy')) {
            $sortDirection = $request->sortDesc === 'desc' ? 'desc' : 'asc';

            switch ($request->sortBy) {
                case 'name':
                    $advertised->orderBy('name', $sortDirection);
                    break;
        
                case 'subcat':
                    $advertised->orderBy('jobs_subcategory.subcategory_name', $sortDirection);
                    break;
    
                case 'active':
                    $advertised->orderBy('active', $sortDirection);
                    break;
        
                default:
                    $advertised->orderBy('created_at', $sortDirection);
            }
        }
        $paginator = $advertised->paginate(5);

        return response()->json(['status' => 'success', 'list'=> $paginator]);
    }
}

?>
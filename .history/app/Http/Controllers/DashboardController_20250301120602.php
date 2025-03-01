<?php

namespace App\Http\Controllers;

use App\Models\Application;
use Inertia\Inertia;

class DashboardController extends Controller{
    public function index(){
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
            ->paginate(10);
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
            ];
        });
        $applications->setCollection($jobs);
        return Inertia::render('Dashboard',[
            'myjobs' => $applications,
        ]);
    }

    public function getAppliedJobsTable()
{

    $applications = Application::with([
        'job_vacancy' => function($query) {
            $query->select('id','name','address','active');
        },
        'job_vacancy.companies',
        'job_status'
    ])
    ->where('user_id', auth()->id())
    ->where('is_deleted', '!=', 1)
    ->paginate(1);

    $transformed = $applications->getCollection()->map(function ($app) {
        return [
            'job_id' => $app->id,
            'job_name' => $app->job_vacancy->name ?? 'N/A',
            'job_cname' => $app->job_vacancy->jobs_subcategory->name ?? 'N/A',
            'job_status_name' => $app->job_status->name ?? 'N/A',
            'job_active' => $app->job_status->name ?? 'N/A'
        ];
    });

    $applications->setCollection($transformed);

    return response()->json(['status' => 'success', 'list'=> $applications]);
}
}

?>
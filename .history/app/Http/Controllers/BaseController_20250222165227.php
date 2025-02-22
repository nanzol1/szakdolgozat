<?php

namespace App\Http\Controllers;
use App\Models\JobVacancy;
use Inertia\Inertia;
use Route;

class BaseController extends Controller{
    public function index(Request $request){
        $jobvacancy = JobVacancy::with('companies','jobs_category','jobs_subcategory')
        ->where('active','=','1')->inRandomOrder()->limit(10)->get();
        $query = JobVacancy::with('companies','jobs_category','jobs_subcategory')
        ->where('active','=','1')->paginate(5);
        $query = 
        return Inertia::render('Welcome', [
            'canLogin' => Route::has('login'),
            'canRegister' => Route::has('register'),
            'featured_jobs' => $jobvacancy,
            'jobs' => $alljob,
            'filters' => $request->only['search'],
        ]);
    }
}

?>
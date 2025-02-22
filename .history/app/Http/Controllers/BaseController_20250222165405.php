<?php

namespace App\Http\Controllers;
use App\Models\JobVacancy;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Route;

class BaseController extends Controller{
    public function index(Request $request){
        $jobvacancy = JobVacancy::with('companies','jobs_category','jobs_subcategory')
        ->where('active','=','1')->inRandomOrder()->limit(10)->get();
        $alljob = JobVacancy::with('companies','jobs_category','jobs_subcategory')
        ->where('active','=','1')->paginate(5);
        if($request->filled('search')){
            $query->where('name','LIKE','%'.$request->search.'%');
        }
        if($request->filled('category')){
            $query->whereIn('maincateg_id',$request->category);
        }
        if($request->filled('subcategory')){
            $query->whereIn('subcateg_id',$request->subcategory);
        }
        return Inertia::render('Welcome', [
            'canLogin' => Route::has('login'),
            'canRegister' => Route::has('register'),
            'featured_jobs' => $jobvacancy,
            'jobs' => $alljob,
            'filters' => $request->only(['search','category','subcategory']),
        ]);
    }
}

?>
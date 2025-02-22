<?php

namespace App\Http\Controllers;
use App\Models\JobCategory;
use App\Models\JobVacancy;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Route;

class BaseController extends Controller{
    public function index(Request $request){
        $jobvacancy = JobVacancy::with('companies','jobs_category','jobs_subcategory')
        ->where('active','=','1')->inRandomOrder()->limit(10)->get();
        $query = JobVacancy::with('companies','jobs_category','jobs_subcategory')
        ->where('active','=','1');
        $categories = JobCategory::with('jobs_subcategories')->get()->toArray();
        $cleanedCats = [];
        foreach($categories as $cats){
            $cleanedCats[$cats['id']] = [
                'mid' => $cats['id'],
                'name' => $cats['name'],
                'subcategories' => [],
            ];
            foreach($cats['jobs_subcategories'] as $subs){
                $cleanedCats[$cats['id']]['subcategories'][] = [
                    'subcat_id' => $subs['id'],
                    'subcat_name' => $subs['name'],
                    'subcat_keyid' => $subs['key_id'],
                    'maincateg_id' => $subs['maincateg_id'],
                ];
            }
        }
        if($request->filled('search')){
            $query->where('name','LIKE','%'.$request->search.'%');

        }
        if($request->filled('category')){
            $query->whereIn('maincateg_id',$request->category);
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
        $alljob = JobVacancy::with('companies','jobs_category','jobs_subcategory')
        ->where('active','=','1')->paginate(5);
        return Inertia::render('Welcome', [
            'canLogin' => Route::has('login'),
            'canRegister' => Route::has('register'),
            'featured_jobs' => $jobvacancy,
            'filters' => $request->only(['search','category','subcategory']),
            'categories' => $cleanedCats,
            'jobs' => $alljob,
            'min_spayment' => $min_payment,
            'max_spayment' => $max_payment,
        ]);
    }

    public function filterJobs(Request $request){
        $query = JobVacancy::with('companies','jobs_category','jobs_subcategory')
        ->where('active','=','1');

        if($request->filled('search')){
            $query->where('name','LIKE','%'.$request->search.'%');

        }
        if($request->filled('category')){
            $query->whereIn('maincateg_id',$request->category);
        }
        if($request->filled('min_payment') && $request->filled('min_payment')){
            $minPayment = (int) $request->input('min_payment');
            $maxPayment = (int) $request->input('max_payment');

            if(is_int($minPayment) && is_int($maxPayment) && $minPayment <= $maxPayment){
                $query->whereBetween('payment',[$minPayment,$maxPayment]);
            }
        }
        $alljob = $query->paginate(5);
        $request->session()->flash('message', 'fff');


        return redirect()->back()->with('message','Sikeres agyfasz');
    }
}

?>
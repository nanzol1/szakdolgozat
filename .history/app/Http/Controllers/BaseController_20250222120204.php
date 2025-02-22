<?php

namespace App\Http\Controllers;
use App\Models\JobVacancy;
use Inertia\Inertia;
use Route;

class BaseController extends Controller{
    public function index(){
        $jobvacancy = JobVacancy::inRandomOrder(10)->limit(10)->get();
        return Inertia::render('Welcome', [
            'canLogin' => Route::has('login'),
            'canRegister' => Route::has('register'),
            'featured' => $jobvacancy,
        ]);
    }
}

?>
<?php

namespace App\Http\Controllers;
use Inertia\Inertia;
use Route;

class BaseController extends Controller{
    public function index(){
        return Inertia::render('Welcome', [
            'canLogin' => Route::has('login'),
            'canRegister' => Route::has('register'),
        ]);
    }
}

?>
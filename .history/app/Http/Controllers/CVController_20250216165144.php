<?php

namespace App\Http\Controllers;
use Inertia\Inertia;
class CVController extends Controller{
    public function index(){
        return Inertia::render("Profile/UserProfile/Cv/CreateCv");
    }

    public function store(Request $request){
        dd($request->all());
     }
}

?>
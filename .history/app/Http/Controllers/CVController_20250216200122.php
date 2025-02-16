<?php

namespace App\Http\Controllers;
use App\Models\Education;
use Inertia\Inertia;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
class CVController extends Controller{
    public function index(){
        Education::all();
        return Inertia::render("Profile/UserProfile/Cv/CreateCv");
    }

    public function store(Request $request){
        dd($request->all());
     }
}

?>
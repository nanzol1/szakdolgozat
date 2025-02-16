<?php

namespace App\Http\Controllers;
use App\Models\Education;
use Inertia\Inertia;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use Validator;
class CVController extends Controller{
    public function index(){
        return Inertia::render("Profile/UserProfile/Cv/CreateCv");
    }

    public function store(Request $request){
        dd($request->all());
        $datas = $request->all();

        if($datas){
            $validated = Validator::make($datas,[
                ""
            ]);
        }
    }

     public function generatePdf($id){
    }
}

?>
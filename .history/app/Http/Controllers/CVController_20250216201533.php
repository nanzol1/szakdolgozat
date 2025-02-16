<?php

namespace App\Http\Controllers;
use App\Models\Education;
use App\Models\Prepared_CV;
use Inertia\Inertia;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use Validator;
class CVController extends Controller{
    public function index(){
        return Inertia::render("Profile/UserProfile/Cv/CreateCv");
    }

    public function store(Request $request){
        $datas = $request->all();
        if($datas){
            $validated = Validator::make($datas,[
                //"firstname" => "string|required|min:3|max:255",
                //"lastname" => "string|required|min:3|max:255",
                //"phone" => "string|required",
                //"email" => "email|required",
                //"county" => "nullable|string",
                //"city" => "nullable|string",
                //"address" => "nullable|string",
                //"zip" => "nullable|string",
                //"exps" => "array|nullable",
                //"educations" => "array|nullable",
                //"skills" => "array|nullable",
                "more_desc" => "string|nullable",
                "plain_text" => "string|nullable",
            ])->validate();
            dd($validated);
            Prepared_CV::create($validated);
        }
    }

    /*public function generatePdf($id){
    }*/
}

?>
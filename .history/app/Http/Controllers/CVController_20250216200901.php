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
                "firstname" => "string|required|min:3|max:255",
                "lastname" => "string|required|min:3|max:255",
                "phone" => "string|required",
                "email" => "email|required",
                "county" => 'nullable',
                "city" => 'nullable',
                "address" => 'nullable',
                "zip" => 'nullable',
                "exps" => 'array|nullable',
                "educations" => 'array|nullable',
                "skills" => 'array|nullable',
                "more_desc" => "string|nullable",
                "plain_text" => "string|nullable",
            ])->validate();


        }
    }

     public function generatePdf($id){
    }
}

?>
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
                "country" => ''
                "city" => null
                "address" => null
                "zip" => null
                "exps" => array:1 [▶]
                "educations" => array:1 [▶]
                "skills" => array:1 [▶]
                "more_desc" => "<p>asdasdsa</p>"
                "plain_text" => "asdasdsa"
            ]);
        }
    }

     public function generatePdf($id){
    }
}

?>
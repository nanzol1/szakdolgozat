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
        dd($datas);
        if($datas){
            $validated = Validator::make($datas,[
                "cv_picture" => 'nullable|file',
                "firstname" => "string|required|min:0|max:255",
                "lastname" => "string|required|min:0|max:255",
                "phone" => "string|required",
                "email" => "email|required",
                "county" => "nullable|string",
                "city" => "nullable|string",
                "address" => "nullable|string",
                "zip" => "nullable|string",
                "exps" => "array|nullable",
                "educations" => "array|nullable",
                "skills" => "array|nullable",
                "more_desc" => "string|nullable",
                "plain_text" => "string|nullable",
            ])->validate();
            
            if($request->hasFile('cv_picture')){
                $path = $request->file('cv_picture');
                $validated['cv_picture'] = $path->hashName();
                $path->store('uploads/cv_maker/','public');
            }
            
            $cv_id = Prepared_CV::create($validated);

            return response()->json(['message' => 'CV sikeresen elmentve!','cv_id' => $cv_id]);
        }
    }

    public function generatePdf($id){
        $cv = Prepared_CV::find($id);
        $file_name = $cv['firstname'].'_'.$cv['lastname'];

        $pdf = PDF::loadView("cv_templates/cv-template",[
            'cv' => $cv,
        ]);

        return $pdf->download($file_name.'.pdf');
    }
}

?>
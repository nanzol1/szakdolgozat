<?php

namespace App\Http\Controllers;
use App\Models\Prepared_CV;
use Inertia\Inertia;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use Route;
use Validator;
use App\Facades\LogFacade as Log;



class CVController extends Controller{
    public function index(){
        return Inertia::render("Profile/UserProfile/Cv/CreateCv",[
            'canLogin' => Route::has('login'),
            'canRegister' => Route::has('register'),
        ]);
    }

    public function showCVTemplate(Request $request){
        $cv = $request->all();
        return view('cv_templates/cv-template',$cv);
    }

    public function store(Request $request){
        $datas = $request->all();
        $datas['exps'] = json_decode($datas['exps'],true);
        $datas['educations'] = json_decode($datas['educations'],true);
        $datas['skills'] = json_decode($datas['skills'],true);
        if($datas){
            $validated = Validator::make($datas,[
                "cv_picture" => 'nullable|image',
                "firstname" => "string|required|min:0|max:255",
                "lastname" => "string|required|min:0|max:255",
                "phone" => "string|required",
                "email" => "email|required",
                "county" => "nullable|string",
                "city" => "nullable|string",
                "address" => "nullable|string",
                "zip" => "nullable|string",
                "exps" => "nullable",
                "educations" => "nullable",
                "skills" => "nullable",
                "more_desc" => "string|nullable",
                "plain_text" => "string|nullable",
            ])->validate();
            if($request->hasFile('cv_picture')){
                $path = $request->file('cv_picture');
                $validated['cv_picture'] = $path->hashName();
                $path->store('uploads/cv_maker/','public');
            }
            
            $cv_id = Prepared_CV::create($validated);
            Log::logUserAction('create','Létrehozott egy új CV-t',auth('web')->user()->id);

            return response()->json(['message' => 'CV sikeresen elmentve!','cv_id' => $cv_id]);
        }
    }

    public function generatePdf(Request $request){
        // HTML tartalom és fájlnév kinyerése
        $html = $request->input('html');
        $fileName = $request->input('fileName');

        // PDF generálása
        $pdf = PDF::loadHTML($html);
        // PDF visszaküldése letöltésre
        return $pdf->download($fileName);
    }
}

?>
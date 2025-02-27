<?php

namespace App\Http\Controllers;
use App\Models\Prepared_CV;
use Barryvdh\DomPDF\Facade\Pdf;
use Inertia\Inertia;
use Illuminate\Http\Request;
use Route;
use Validator;
use App\Facades\LogFacade as Log;
use Barryvdh\Snappy\Facades\SnappyPdf;



class CVController extends Controller{
    public function index(){
        return Inertia::render("Profile/UserProfile/Cv/CreateCv",[
            'canLogin' => Route::has('login'),
            'canRegister' => Route::has('register'),
        ]);
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

    public function generatePDF(Request $request)
    {
        $html = json_encode($request->input('html'), JSON_UNESCAPED_UNICODE);
    
        dd($html);

        $pdf = SnappyPdf::loadHTML($html)
            ->setPaper('a4')
            ->setOption('encoding', 'UTF-8')
            ->setOption('margin-top', 0)
            ->setOption('margin-bottom', 0)
            ->setOption('margin-left', 0)
            ->setOption('margin-right', 0);
    
        return $pdf->download('document.pdf');
    }
}

?>
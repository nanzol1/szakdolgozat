<?php

namespace App\Http\Controllers;

use App\Models\JobCategory;
use Carbon\Carbon;
use Date;
use Hash;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Storage;
use Inertia\Inertia;
use Inertia\Response;
use App\Models\User;
use App\Models\Education;
use App\Models\Institution;
use App\Facades\LogFacade as Log;

class ProfileController extends Controller
{
    /**
     * Display the user's profile form.
     */
    public function edit(Request $request): Response
    {
        $categories = JobCategory::with('jobs_subcategories')->get()->toArray();
        $category = [];
        foreach($categories as $item){
            $category[$item['id']] = [
                'id' => $item['id'],
                'name' => $item['name'],
                'subcategories' => $item['jobs_subcategories'] ?? [],
            ];
        }
        return Inertia::render('Profile/UserProfile/Edit', [
            'mustVerifyEmail' => $request->user() instanceof MustVerifyEmail,
            'status' => session('status'),
            'category' => $category,
        ]);
    }

    public function editInterests(Request $request): Response
    {
        $categories = JobCategory::with('jobs_subcategories')->get()->toArray();
        $category = [];
        $jobs_interests = $request->user()->jobs_interests;
        foreach($categories as $item){
            $category[$item['id']] = [
                'id' => $item['id'],
                'name' => $item['name'],
                'subcategories' => $item['jobs_subcategories'] ?? [],
            ];
        }
        return Inertia::render('Profile/UserProfile/EditInterests', [
            'mustVerifyEmail' => $request->user() instanceof MustVerifyEmail,
            'status' => session('status'),
            'category' => $category,
            'jobs_interests' => $jobs_interests,

        ]);
    }

    public function editEducations(Request $request): Response
    {
        $educations = Education::where('user_id','=',$request->user()->id)->get();
        return Inertia::render('Profile/UserProfile/EditEducation', [
            'mustVerifyEmail' => $request->user() instanceof MustVerifyEmail,
            'status' => session('status'),
            'educations' => $educations,
        ]);
    }

    public function editCV(Request $request): Response
    {
        return Inertia::render('Profile/UserProfile/EditCv', [
            'mustVerifyEmail' => $request->user() instanceof MustVerifyEmail,
            'status' => session('status'),
        ]);
    }

    public function getInstitutions(Request $request){
        $query = $request->institution_name;
        if(is_array($query)){
            $query = $request->institution_name['title'];
        }
        $institutions = Institution::where('institution_name','LIKE','%'.$query.'%')->get();

        return response()->json([
            'institutions' => $institutions,
            'query' => $query,
        ]);

    }
    public function deteleEducation(Request $request){
        $id = $request->id;
        $education = Education::where('id','=',$id)->first();
        if($id && $education){
            $education->delete();
        }
    }
    /**
     * Update the user's profile information.
     */
    public function update(Request $request): RedirectResponse
    {

        $user = User::where('email', $request->email)->first();
        $needChange = [];
        foreach($request->all() as $key => $value){
            if($user->$key !== $value){
                $needChange[$key] = $value;
            }
        }

        if($request->hasFile('profile_photo_path')){
            if($user->profile_photo_path){
                Storage::disk('public')->delete('uploads/profile_picture/'.$user->id.'/'.$user->profile_photo_path);
                $request->user()->update(['profile_photo_path'=> null]);
            }

            $path = $request->file('profile_photo_path');
            $needChange['profile_photo_path'] = $path->hashName();
        }
        if(array_key_exists('birth_date',$needChange)){
            $birth_date = Carbon::createFromTimestampMsUTC($needChange['birth_date']);
            $birth_date->setTimezone('Europe/Budapest');
            $needChange['birth_date'] = $birth_date;
        }

        
        $validated = Validator::make($needChange,[
            'username' => 'String|min:3|max:255|unique:users',
            'name' => 'string|min:3',
            'sex' => 'string',
            'birth_date' => 'date',
            'phone_number' => 'nullable|string',
            'county' => 'nullable|string',
            'zip' => 'nullable|integer',
            'city' => 'nullable|string',
            'address' => 'nullable|string',
        ])->validate();

        $request->user()->update(['driverLicense' => $request->driverLicense]);


        if($request->user()->update($validated)){
            if($request->hasFile('profile_photo_path')){
                $path->store('uploads/profile_picture/'.$request->user()->id.'/','public');
                $request->user()->update(['profile_photo_path' => $path->hashName()]);
            }
        }

        Log::logUserAction('update','Frissítette az adatait',auth('web')->user()->id);
        
        return Redirect::route('profile.edit')->with('success','Az adatok sikeresen módosultak!');
    }

    public function updateIntroduction(Request $request){
        $intro = $request->all();
        if($intro){
            $validate = Validator::make($intro,[
                'introduction' => 'nullable|string|min:3|max:255',
            ])->validate();

            Log::logUserAction('update','Frissítette az adatait',auth('web')->user()->id);

            $request->user()->update($validate);
        }
    }

    public function updateInterests(Request $request){
        Log::logUserAction('update','Frissítette az adatait',auth('web')->user()->id);
        $request->user()->update(['jobs_interests' => $request->interests]);
    }

    public function destroycv(Request $request){
        $user = User::where('email', $request->user()->email)->first();
        if($user->cv_file){
            Storage::disk('public')->delete('uploads/cv/'.$user->cv_file);
            $request->user()->update(['cv_file' => null]);
        }
        Log::logUserAction('update','Frissítette az adatait',auth('web')->user()->id);
    }

    public function updateEducation(Request $request){
        $datas = $request->all();

        $id = null;
        if(array_key_exists('id',$datas)){
            $id = $datas['id'];
            $old = Education::where('id','=',$id)->first();
        }

        if($datas){
            if($datas['education_type'] !== 'Egyetem' && $datas['education_type'] !== 'Főiskola'){
                $datas['education_level'] = null;
            }
            if($datas['date_from'] !== null && array_key_exists('date_from',$datas)){
                $date_from = Carbon::createFromTimestampMsUTC($datas['date_from']);
                $date_from->setTimezone('Europe/Budapest');
                $datas['date_from'] = $date_from;
            }
            if($datas['date_to'] !== null && array_key_exists('date_to',$datas)){
                $date_to = Carbon::createFromTimestampMsUTC($datas['date_to']);
                $date_to->setTimezone('Europe/Budapest');
                $datas['date_to'] = $date_to;
            }

            $validated = Validator::make($datas, [
                'education_type' => 'required|string',
                'institution_name' => 'required|string|min:3|max:255',
                'education_name' => 'required|string|min:3|max:255',
                'education_level' => 'nullable|string',
                'date_from' => 'required|date',
                'date_to' => 'required|date',
                'education_place' => 'required|string',
            ],[
                'education_type.required' => 'Ez a mező kötelező!',
                'institution_name.required' => 'Ez a mező kötelező!',
                'education_name.required' => 'Ez a mező kötelező!',
                'date_from.required' => 'Ez a mező kötelező!',
                'date_to.required' => 'Ez a mező kötelező!',
                'education_place.required' => 'Ez a mező kötelező!',
            ])->validate();

            $validated['user_id'] = $request->user()->id;

            Log::logUserAction('update','Frissítette az adatait',auth('web')->user()->id);

            if($id){
                $old->update($validated);
            }else{
                Education::create($validated);
            }

            return back()->with('success','Sikeres mentés!');
        }
    }

    public function createPinCode(Request $request){
        $pin_code = $request->all();
        $user = User::where('email','=',$request->user()->email)->first();
        if($pin_code && $user){
            $validated = Validator::make($pin_code,[
                'pin_code' => 'required|string|min:4|max:4',
            ])->validate();

            $request->user()->update([
                'cv_pin' => Hash::make($validated['pin_code']),
            ]);
        }
        return redirect()->back()->with('success','Sikeres végrehajtás!');
    }

    public function getValidPinCode(Request $request){
        $user = User::where('email','=',$request->user()->email)->first();
        
        if($user && Hash::check($request->pin_code, $user->cv_pin)){
            return response()->json(['status' => 'success','pin'=> 'PIN kód érvényes']);
        }

        return response()->json(['status' => 'error', 'pin' => 'Érvénytelen PIN kód']);
    }

    public function uploadProfileCV(Request $request){
        $data = $request->all();
        $user = User::where('email', $request->user()->email)->first();

        if($request->hasFile('cv_file')){
            $validated = Validator::make($data, [
                'cv_file' => 'required|file|mimes:pdf,doc,docx,odt|max:5120',
            ],[
                'cv_file.required' => 'Kötelező mező',
                'cv_file.mimes' => 'Az önélrajz csak pdf, doc, docx, odt formátumú lehet!',
            ])->validate();

            if($validated){
                $file = $request->file('cv_file');
                
                if($user->profile_photo_path){
                    Storage::disk('public')->delete('uploads/cv/'.$user->cv_file);
                    $request->user()->update(['cv_file'=> null]);
                }

                $file->store('uploads/cv', 'public');

                $request->user()->update(['cv_file' => $file->hashName()]);
            }

            Log::logUserAction('update','Frissítette az adatait',auth('web')->user()->id);
            return redirect()->back()->with('success','Az önéletrajzát sikeresen feltöltötte!');
        }
        return redirect()->back()->with('error','Valami hiba történt a feltöltés során!');

    }


    /**
     * Delete the user's account.
     */
    public function destroy(Request $request): RedirectResponse
    {
        $request->validate([
            'password' => ['required', 'current_password'],
        ]);

        $user = $request->user();

        Auth::logout();

        $user->update(['is_active' => 0]);

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return Redirect::to('/');
    }
}

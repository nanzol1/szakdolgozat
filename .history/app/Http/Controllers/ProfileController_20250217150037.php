<?php

namespace App\Http\Controllers;

use App\Models\JobCategory;
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

class ProfileController extends Controller
{
    /**
     * Display the user's profile form.
     */
    public function edit(Request $request): Response
    {
        $categories = JobCategory::with('jobs_subcategories')->get();
        $category = [];
        $jobs_interests = $request->user()->jobs_interests;
        foreach($categories as $item){
            $category[$item->id] = [
                'id' => $item->id,
                'name' => $item->name,
                'subcategories' => $item->jobs_subcategories->toArray() ?? [],
            ];
        }
        return Inertia::render('Profile/UserProfile/Edit', [
            'mustVerifyEmail' => $request->user() instanceof MustVerifyEmail,
            'status' => session('status'),
            'category' => $category,
            'jobs_interests' => $jobs_interests,

        ]);
    }

    public function getInstitutions(Request $request){
        $query = $request->institution_name;

        $institutions = Institution::where('institution_name','LIKE','%'.$query.'%')->get();

        return response()->json([
            'institutions' => $institutions,
            'query' => $query,
        ]);

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

        if($request->hasFile('cv_file')){
            if($user->cv_file){
                Storage::disk('public')->delete('uploads/cv/'.$user->cv_file);
            }

            $path = $request->file('cv_file');
            $needChange['cv_file'] = $path->hashName();
        }

        
        
        $validated = Validator::make($needChange,[
            'username' => 'String|min:3|max:255|unique:users',
            //'email' => 'email',
            'name' => 'string|min:3',
            'sex' => 'string',
            'birth_date' => 'date',
            'phone_number' => 'nullable|string',
            'county' => 'nullable|string',
            'zip' => 'nullable|string',
            'city' => 'nullable|string',
            //'driverLicense' => 'nullable|array',
        ])->validate();


        $request->user()->update(['driverLicense' => $request->driverLicense]);


        if($request->user()->update($validated)){
            if($request->hasFile('cv_file')){
                $path->store('uploads/cv','public');
                $request->user()->update(['cv_file' => $path->hashName()]);
            }
        }

        return Redirect::route('profile.edit');
    }

    public function updateIntroduction(Request $request){
        $intro = $request->all();
        if($intro){
            $validate = Validator::make($intro,[
                'introduction' => 'nullable|string|min:3|max:255',
            ])->validate();


            $request->user()->update($validate);
        }
    }

    public function updateInterests(Request $request){
        $request->user()->update(['jobs_interests' => $request->interests]);

    }

    public function destroycv(Request $request){
        $user = User::where('email', $request->email)->first();
        if($user->cv_file){
            Storage::disk('public')->delete('uploads/cv/'.$user->cv_file);
            $request->user()->update(['cv_file' => null]);
        }
    }

    public function updateEducation(Request $request){
        $datas = $request->all();

        if($datas){
            $validated = Validator::make($datas, [
                'education_type' => 'string',
                'institution_name' => 'nullable|string|min:3|max:255',
                'education_name' => 'nullable|string|min:3|max:255',
                'education_level' => 'nullable|string',
                'date_from' => 'nullable|date',
                'date_to' => 'nullable|date',
                'education_place' => 'string',
            ])->validate();
            $validated['user_id'] = $request->user()->id;

            //TODO Visszajelzést adni a usernek hogy sikerült
            Education::create($validated);
        }
    }

    public function uploadProfilePicture(Request $request){
        $image = $request->files;
        dd($image);
        if($image){
            $validated = Validator::make($request->profile_photo, [
                'profile_photo_path' => 'required|image',
            ])->validate();

            if($validated){
                
                $image->store('uploads/profile_picture/'.$request->user()->id, 'public');

                $request->user()->update(['profile_photo_path' => $image->hashName()]);
            }
        }
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

        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return Redirect::to('/');
    }
}

<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use App\Models\JobCategory;
use App\Models\JobSubCategory;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Storage;
use Inertia\Inertia;
use Inertia\Response;
use Illuminate\Validation\Rules\Password;
use App\Models\User;

class ProfileController extends Controller
{
    /**
     * Display the user's profile form.
     */
    public function edit(Request $request): Response
    {
        $categories = JobCategory::with('jobs_subcategories')->get();
        $category = [];
        foreach($categories as $item){
            $category[$item->id] = [
                'id' => $item->id,
                'name' => $item->name,
                'subcategories' => $item->jobs_subcategories->toArray(),
            ];
        }

        return Inertia::render('Profile/UserProfile/Edit', [
            'mustVerifyEmail' => $request->user() instanceof MustVerifyEmail,
            'status' => session('status'),
            'category' => $category,
        ]);
    }

    public function editCompany(Request $request): Response
    {
        return Inertia::render('Profile/CompanyProfile/Edit', [
            'mustVerifyEmail' => $request->user() instanceof MustVerifyEmail,
            'status' => session('status'),
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
            //'name' => 'string',
            'sex' => 'string',
            'birth_date' => 'date',
            'phone_number' => 'nullable|string',
            'county' => 'nullable|string',
            'zip' => 'nullable|string',
            'city' => 'nullable|string',
        ])->validate();


        if($request->user()->update($validated)){
            if($request->hasFile('cv_file')){
                $path->store('uploads/cv','public');
                $request->user()->update(['cv_file' => $path->hashName()]);
            }
        }

        return Redirect::route('profile.edit');
    }

    public function updateInterests(Request $request){
        //TODO Megcsinálni a kérést hogy feldolgozza rendesen, és elküldeni adatbázisba
        $interests = $request->interests;
        if($interests){
            dd(trim('m',$interests));
        }
        //dd($request->interests);
    }

    public function destroycv(Request $request){
        $user = User::where('email', $request->email)->first();
        if($user->cv_file){
            Storage::disk('public')->delete('uploads/cv/'.$user->cv_file);
            $request->user()->update(['cv_file' => null]);
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

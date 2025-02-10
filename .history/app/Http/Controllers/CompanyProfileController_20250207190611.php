<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Redirect;
use App\Models\Company;
use App\Models\JobVacancy;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;


class CompanyProfileController extends Controller{

    public function editCompany(Request $request)
    {
        return Inertia::render('Profile/CompanyProfile/Edit', [
            'mustVerifyEmail' => $request->user() instanceof MustVerifyEmail,
            'status' => session('status'),
        ]);
    }
    public function update(Request $request): RedirectResponse
    {
        $user = Company::where('email', $request->email)->first();
        $needChange = [];
        foreach($request->all() as $key => $value){
            if($user->$key !== $value){
                $needChange[$key] = $value;
            }
        }

        if($request->hasFile('cv_file')){
            if($user->cv_file){
                Storage::disk('public')->delete('uploads/company_profile/'.$user->cv_file);
            }

            $path = $request->file('cv_file');
            $needChange['cv_file'] = $path->hashName();
        }

        
        
        $validated = Validator::make($needChange,[
            'company_name' => 'String|min:3|max:255|unique:companies',
            //'email' => 'email',
            'company_pnumber' => 'string|min:3',
            'company_address' => 'nullable|string|min:3',
            'company_desc' => 'nullable|string|min:3',
        ])->validate();


        $request->user()->update(['driverLicense' => $request->driverLicense]);


        if($request->user()->update($validated)){
            if($request->hasFile('cv_file')){
                $path->store('uploads/cv','public');
                $request->user()->update(['cv_file' => $path->hashName()]);
            }
        }

        return Redirect::route('cprofile.edit');
    }

    public function createJobs(Request $request){
        $datas = $request->all();

        if($datas){
            $validated = Validator::make($datas,[
                'name' => 'String|required|min:3|max:255',
                'position' => 'String|required|min:3|max:255',
                'description' => 'String|required|min:3|max:255',
                'requirements' => 'String|required|min:3|max:255',
                'payment' => 'nullable|String|min:3|max:255',
                'created_by' => 'Integer',
            ])->validate();

            JobVacancy::create($validated);
        }
    }
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
?>
<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Redirect;
use App\Models\Company;


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

        /*if($request->hasFile('cv_file')){
            if($user->cv_file){
                Storage::disk('public')->delete('uploads/cv/'.$user->cv_file);
            }

            $path = $request->file('cv_file');
            $needChange['cv_file'] = $path->hashName();
        }*/

        
        
        $validated = Validator::make($needChange,[
            'company_name' => 'String|min:3|max:255|unique:companies',
            //'email' => 'email',
            'company_pnumber' => 'string|min:3',
            'company_address' => 'string|min:3',
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

    public function destroycv(Request $request){
        $user = User::where('email', $request->email)->first();
        if($user->cv_file){
            Storage::disk('public')->delete('uploads/cv/'.$user->cv_file);
            $request->user()->update(['cv_file' => null]);
        }
    }

}
?>
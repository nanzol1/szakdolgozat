<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Company;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Auth\Events\Registered;
use Illuminate\Validation\Rules\Password;
use Inertia\Inertia;


class RegisterController extends Controller{


    public function create(Request $request){
        return Inertia::render("Auth/Register",[
            'type' => $request->route('type'),
        ]);
    }

    public function storeDatas(Request $request){
        $type = $request->route('type');

        if($type === 'munkavallalo'){

            $request->validate([
                'username' => 'required|max:255',
                'email' => 'required|email|unique:users',
                'name' => 'required|max:255',
                'password' => ['required','string','min:8', Password::default()],
                'cv_file' => 'file|mimes:pdf,doc,docx,odt,jpg,png|max:2048',
            ]);

            $user = User::create([
                'username'=> $request->username,
                'email'=> $request->email,
                'name'=> $request->name,
                'password'=> Hash::make($request->password),
                'cv_file' => $request->file('cv')->hashName(),
            ]);

            if(event(new Registered($user))){
                if($request->hasFile('cv')){
                    $request->file('cv')->store('uploads','public');
                }
            }

            Auth::login($user);


            return redirect()->route('dashboard');

        }elseif($type === 'munkaltato'){
            $request->validate([
                'company_name' => 'required|max:255',
                'password' => ['required','string','min:8', Password::default()],
                'email' => 'required|email|unique:companies',
            ]);

            $company = Company::create([
                'company_name' => $request->company_name,
                'password' => Hash::make($request->password),
                'email' => $request->email,
            ]);

            event(new Registered($company));

            Auth::login($company);

            return redirect()->route('dashboard');
        }


    }

}

?>
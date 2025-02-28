<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Company;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Inertia\Inertia;
use Inertia\Response;
use Illuminate\Validation\Rules\Password;


class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     */
    public function create(Request $request): Response
    {
        return Inertia::render('Auth/Register',[
            'type' => $request->route('type'),
        ]);
    }

    /**
     * Handle an incoming registration request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request){
        $type = $request->route('type');

        if($type === 'munkavallalo'){

            $validated = $request->validate([
                'username' => 'required|max:255|unique:users',
                'email' => 'required|email|unique:users',
                'name' => 'required|max:255',
                'password' => ['required','string','min:8', Password::default()],
                'cv_file' => 'file|mimes:pdf,doc,docx,odt,jpg,png,jpeg,bmp|max:5120',
            ],[
                'username.unique' => 'Ez az felhasználónév már foglalt!',
                'email.unique' => 'Ez az email cím már foglalt!',
            ]);

            if($request->hasFile('cv')){
                $validated['cv_file'] = $request->file('cv')->hashName();
                $request->file('cv')->store('uploads','public');
            }
            $validated['password'] = Hash::make($request->password);
            $user = User::create($validated);
            event(new Registered($user));
            Auth::login($user);

            return redirect()->route('cdashboard')->with('success','Sikeres regisztráció!');

        }elseif($type === 'munkaltato'){


            $validated = $request->validate([
                'company_name' => 'required|max:255',
                'password' => ['required','string','min:8', Password::default()],
                'email' => 'required|email|unique:companies',
            ],[
                'email.unique' => 'Ez az email cím már foglalt!',
            ]);


            $company = Company::create([
                'company_name' => $request->company_name,
                'password' => Hash::make($request->password),
                'email' => $request->email,
            ]);

            $validated['password'] = Hash::make($request->password);
            $company = Company::create($validated);
            event(new Registered($company));
            Auth::login($company);

            return redirect()->intended(route('dashboard',absolute:false))->with('success','Sikeres regisztráció!');
        }
        return redirect()->route('register')->with('error','Sikertelen regisztráció!');
    }
}

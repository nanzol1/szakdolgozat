<?php

namespace App\Http\Controllers\Auth;

use App\Events\UserRegistered;
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
use Illuminate\Support\Facades\Mail;

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

            $isExists = User::where('email','=',$request->email)
            ->orWhere('username','=',$request->username)->first();
            
            if($isExists){
                return redirect()->back()->with('error','Ez az email cím, vagy felhasználónév már használatban van!');
            }

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
                $request->file('cv')->store('uploads/cv','public');
            }
            $validated['password'] = Hash::make($request->password);
            $user = User::create($validated);
            event(new Registered($user));
            event(new UserRegistered($user));
            Auth::login($user);

            return redirect()->route('/')->with('success','Sikeres regisztráció!');

        }elseif($type === 'munkaltato'){

            $isExists = Company::where('email','=',$request->email)
            ->orWhere('company_name','=',$request->company_name)->first();

            if($isExists){
                return redirect()->back()->with('error','Ez az email cím, vagy cégnév már használatban van!');
            }

            $validated = $request->validate([
                'company_name' => 'required|max:255|unique:companies',
                'password' => ['required','string','min:8', Password::default()],
                'email' => 'required|email|unique:companies',
            ],[
                'email.unique' => 'Ez az email cím már foglalt!',
                'company_name.unique' => 'Ez az cégnév már használatban van!',
            ]);


            $company = Company::create([
                'company_name' => $request->company_name,
                'password' => Hash::make($request->password),
                'email' => $request->email,
            ]);

            event(new Registered($company));
            Auth::guard('company')->login($company);

            return redirect()->intended(route('/',absolute:false))->with('success','Sikeres regisztráció!');
        }
        return redirect()->route('register')->with('error','Sikertelen regisztráció!');
    }
}

<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Admin\Admin;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use Inertia\Response;
use App\Models\User;
use App\Models\Company;
use App\Facades\LogFacade as Log;


class AuthenticatedSessionController extends Controller
{
    /**
     * Display the login view.
     */
    public function create(): Response
    {
        return Inertia::render('Auth/Login', [
            'canResetPassword' => Route::has('password.request'),
            'status' => session('status'),
        ]);
    }

    /**
     * Handle an incoming authentication request.
     */
    public function store(Request $request): RedirectResponse
    {
        $credentials = $request->only('email','password');
        if($request->logintype === 'munkavallalo'){
            $user = User::where('email', $request->email)->where('is_active','!=','0')->first();
            if($user && Auth::guard('web')->attempt($credentials)) {
                Auth::guard('web')->login($user);

                session()->put('is_company',0);


                Log::logUserAction('bejelentkezes','Bejelentkezést hajtott végre',auth('web')->user()->id);

                return redirect()->intended(route('/', absolute: false))->with('Sikeres bejelentkezés!');;

            }
        }elseif($request->logintype === 'munkaltato'){
            $company = Company::where('email', $request->email)->first();
            if($company && Auth::guard('company')->attempt($credentials)) {

                Auth::guard('company')->login($company);
                
                session()->put('is_company',1);

                
                Log::logCompanyAction('bejelentkezes','Bejelentkezést hajtott végre',auth('company')->user()->id);
        
                return redirect()->intended(route('/', absolute: false))->with('Sikeres bejelentkezés!');;
            }
        }elseif($request->logintype === 'admin'){
            $admin = Admin::where('email', $request->email)->first();
            if($admin && Auth::guard('admin')->attempt($credentials)) {
                Auth::guard('admin')->login($admin);

                Log::logAction('bejelentkezes','Bejelentkezést hajtott végre',auth('admin')->user()->id);

                return redirect()->intended(route('admin.dashboard', absolute: false))->with('success','Sikeres bejelentkezés!');
            }
        }

        return redirect()->back()->with('error','Hiba történt a bejelentkezés során!');
    }

    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/');
    }
}

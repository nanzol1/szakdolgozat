<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Models\Admin\Admin;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use Inertia\Response;
use App\Models\User;
use App\Models\Company;


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
            $user = User::where('email', $request->email)->first();
            if($user && Auth::guard('web')->attempt($credentials)) {
                Auth::guard('web')->login($user);
                session()->regenerate();
                session()->put('is_company',0);

                return redirect()->intended(route('dashboard', absolute: false));

            }
        }elseif($request->logintype === 'munkaltato'){
            $company = Company::where('email', $request->email)->first();
            if($company && Auth::guard('company')->attempt($credentials)) {
                Auth::guard('company')->login($company);
                
                session()->regenerate();
                session()->put('is_company',1);
        
                return redirect()->intended(route('cdashboard', absolute: false));
            }
        }elseif($request->logintype === 'admin'){
            $admin = Admin::where('email', $request->email)->first();
            if($admin && Auth::guard('admin')->attempt($credentials)) {
                Auth::guard('web')->login($admin);
                session()->regenerate();
                session()->put('is_company',0);

                return redirect()->intended(route('dashboard', absolute: false));

            }
        }

        return redirect()->intended(route('login', absolute: false));
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

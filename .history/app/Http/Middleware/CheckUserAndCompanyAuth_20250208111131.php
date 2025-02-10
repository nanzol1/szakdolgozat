<?php

namespace App\Http\Middleware;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CheckUserAndCompanyAuth{
    public function handle(Request $request, Closure $next){
        if(Auth::guard("web")->check() || Auth::guard("company")->check()){
            
        }
    }
}

?>
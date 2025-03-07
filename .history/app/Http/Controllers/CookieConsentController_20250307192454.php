<?php

namespace App\Http\Controllers;

use Cookie;
use Illuminate\Http\Request;

class CookieConsentController extends Controller{
    public function store(Request $request){
        $consent = $request->consent;

        $request->session()->put('cookie_consent',$consent);
        cookie('cookie_consent',$consent,1);
        Cookie::queue('cookie_consent',$consent,1);
        dd([$_COOKIE,$request->session()]);
    }
}

?>
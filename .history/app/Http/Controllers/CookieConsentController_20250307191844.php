<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class CookieConsentController extends Controller{
    public function store(Request $request){
        $consent = $request->consent;

        $request->session()->put('cookie_consent',$consent);

        dd([$_COOKIE,$_SESSION]);
    }
}

?>
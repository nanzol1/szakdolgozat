<?php

namespace App\Http\Controllers;

use Cookie;
use Illuminate\Http\Request;

class CookieConsentController extends Controller{
    public function store(Request $request){
        $consent = $request->consent;

        $request->session()->put('cookie_consent',$consent);
        //Cookie::queue('cookie_consent',$consent,1440,'/',null,true,false);

        return response()->json(['success' => true,'cookie_consent' => $consent]);
    }
}

?>
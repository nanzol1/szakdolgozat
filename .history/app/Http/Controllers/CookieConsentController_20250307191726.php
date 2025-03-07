<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class CookieConsentController extends Controller{
    public function store(Request $request){
        $consent = $request->all();

        dd($consent);
    }
}

?>
<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Request;

class CookieConsentController extends Controller{
    public function store(Request $request){
        $consent = $request->all();

        dd($consent);
    }
}

?>
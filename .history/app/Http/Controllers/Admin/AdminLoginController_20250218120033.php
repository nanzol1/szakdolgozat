<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Inertia\Inertia;

class AdminLoginController extends Controller{
    public function index(){
        return Inertia::render("Admin/Auth/Login.vue");
    }
}

?>
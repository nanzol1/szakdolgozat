<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Inertia\Inertia;

class AdminLoginController extends Controller{
    public function index(){
        return Inertia::render("Admin/Auth/Login");
    }

    public function dashboard(){
        $registeredUsers = User::all();
        $users['users'] = $registeredUsers->toArray() ?? [];
        $users['count'] = $registeredUsers->count() ?? 0;
        return Inertia::render("Admin/Dashboard",[
            'users' => $users,
        ]);
    }
}

?>
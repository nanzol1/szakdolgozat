<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Application;
use App\Models\Company;
use App\Models\JobVacancy;
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

        $registeredCompanies = Company::all();
        $companies['companies'] = $registeredCompanies->toArray() ?? [];
        $companies['count'] = $registeredCompanies->count() ?? 0;

        $jobs = JobVacancy::all();
        $jobsDetails['jobs'] = $jobs->toArray() ?? [];
        $jobsDetails['count'] = $jobs->count() ?? 0;

        $applications = Application::where('is_deleted','!=','1')->get();
        $apps['apps'] = $applications->toArray() ?? [];
        $apps['count'] = $applications->count() ?? 0;
        return Inertia::render("Admin/Dashboard",[
            'users' => $users,
            'companies' => $companies,
            'jobs' => $jobsDetails,
            'apps' => $apps,
        ]);
    }

    public function getUsers(){
        $users = User::all();
        $cleanedDatas = array_map(function($user){
            return [
                'id'=> $user->id,
                'sex' => $user->sex,
                'name'=> $user->name,
                'email'=> $user->email,
                'cv' => $user->cv_file,
                'profile_picture'=> $user->profile_photo_path,

            ];
        },$users);
        return Inertia::render('Admin/Users/UserList',[
            'users'=> $users,
        ]);
    }
}

?>
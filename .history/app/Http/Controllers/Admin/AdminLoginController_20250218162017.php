<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Application;
use App\Models\Company;
use App\Models\JobVacancy;
use App\Models\User;
use Carbon\Carbon;
use Hash;
use Inertia\Inertia;
use Illuminate\Http\Request;
use Str;


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

    public function getUsers(Request $request){
        $query = User::where('name','LIKE','%'.$request->search.'%');
        $users = $query->get()->toArray();
        $cleanedDatas = array_map(function($user){
            return [
                'id'=> $user['id'],
                'name'=> $user['name'],
                'email'=> $user['email'],
                'cv' => $user['cv_file'] ?? 'Nincs',
                'profile_picture'=> $user['profile_photo_path'] ?? 'Nincs',
                'created_at' => Carbon::create($user['created_at'])->toDateTimeString(),
                'updated_at'=> Carbon::create($user['updated_at'])->toDateTimeString(),
                'last_seen' => $user['last_seen'],
            ];
        },$users);
        return Inertia::render('Admin/Users/UserList',[
            'search' => $request->only(['search']),
            'users'=> $cleanedDatas,
        ]);
    }

    public function showUser($id){
        $user = User::where('id','=',$id)->get()->toArray();
        $cleanedDatas = array_map(function($usr){
            return [
                'id'=> $usr['id'],
                'name'=> $usr['name'],
                'email'=> $usr['email'],
                'cv' => $usr['cv_file'] ?? 'Nincs',
                'profile_picture'=> $usr['profile_photo_path'] ?? 'Nincs',
                'created_at' => Carbon::create($usr['created_at'])->toDateTimeString(),
                'updated_at'=> Carbon::create($usr['updated_at'])->toDateTimeString(),
                'last_seen' => $usr['last_seen'],
            ];
        },$user);
        return Inertia::render('Admin/Users/User',[
            'user' => $cleanedDatas,
        ]);
    }

    public function generateRandomPassword($id){
        $user = User::where('id','=',$id)->first();
        $randomPassword = Str::random(12);
        $hashedPassword = Hash::make($randomPassword);

        $user->update(['password' => $hashedPassword]);
        
    }
}

?>
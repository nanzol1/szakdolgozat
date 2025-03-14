<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Application;
use App\Models\User;
use Illuminate\Support\Carbon;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Str;
use Illuminate\Support\Facades\Hash;
use App\Facades\LogFacade as Log;

class UserController extends Controller{
    
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
        $appliactions = Application::with('job_vacancy','job_status')
        ->where('user_id','=',$id)->get()->toArray();
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
        $cleanedApps = array_map(function($apps){
            return [
                'id'=> $apps['id'],
                'job_id' => $apps['job_id'],
                'active' => $apps['is_deleted'],
                'status' => $apps['job_status']['name'],
                'job_name' => $apps['job_vacancy']['name'],
                'job_createdby' => $apps['job_vacancy']['created_by'],
            ];
        },$appliactions);
        Log::logAction('megtekint_user','Megtekintette az adatait a következő munkavállalónak -> ('.$id.')',auth('admin')->user()->id);

        return Inertia::render('Admin/Users/User',[
            'user' => $cleanedDatas,
            'appliactions' => $cleanedApps,
        ]);
    }

    public function setStatus($id){
        $user = User::where('id','=',$id)->first();
        if($user && $user['is_active'] == 1){
            $user->update(['is_active' => 0]);
        }else{
            $user->update(['is_active' => 1]);
        }
        Log::logAction('modosit_user','Módosította az adatait a következő munkavállalónak -> ('.$id.')',auth('admin')->user()->id);

        return response()->json(['status'=>'success','message'=> 'Státusz sikeresen módosítva -> '.$user['is_active']]);
    }

    public function generateRandomPassword($id){
        $user = User::where('id','=',$id)->first();
        $randomPassword = Str::random(12);
        $hashedPassword = Hash::make($randomPassword);
        Log::logAction('modosit_user','Alaphelyzetbe állította a jelszavát a következő munkavállalónak -> ('.$id.')',auth('admin')->user()->id);

        $user->update(['password' => $hashedPassword]);
    }
}

?>
<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Application;
use App\Models\Company;
use App\Models\JobVacancy;
use Carbon\Carbon;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Str;

class CompanyController extends Controller{
    public function getCompanies(Request $request){
        $query = Company::where('company_name','LIKE','%'.$request->search.'%');
        $companies = $query->get()->toArray();
        $cleanedDatas = array_map(function($company){
            return [
                'id'=> $company['id'],
                'name'=> $company['company_name'],
                'email'=> $company['email'],
                'profile_picture'=> $company['profile_pict'] ?? 'Nincs',
                'created_at' => Carbon::create($company['created_at'])->toDateTimeString(),
                'updated_at'=> Carbon::create($company['updated_at'])->toDateTimeString(),
            ];
        },$companies);
        return Inertia::render('Admin/Companies/CompanyList',[
            'search' => $request->only(['search']),
            'companies'=> $cleanedDatas,
        ]);
    }

    public function showCompany($id){
        $company = Company::where('id','=',$id)->get()->toArray();
        $jobVacancy = JobVacancy::with('applicants')
        ->where('created_by','=',$id)->get()->toArray();
        $cleanedDatas = array_map(function($cmpn){
            return [
                'id'=> $cmpn['id'],
                'name'=> $cmpn['company_name'],
                'email'=> $cmpn['email'],
                'profile_picture'=> $cmpn['profile_pict'] ?? 'Nincs',
                'created_at' => Carbon::create($cmpn['created_at'])->toDateTimeString(),
                'updated_at'=> Carbon::create($cmpn['updated_at'])->toDateTimeString(),
            ];
        },$company);
        $cleanedVacancy = array_map(function($vac){: array
            return [
                'id'=> $cmpn['id'],
                'name'=> $cmpn['company_name'],
                'email'=> $cmpn['email'],
                'profile_picture'=> $cmpn['profile_pict'] ?? 'Nincs',
                'created_at' => Carbon::create($cmpn['created_at'])->toDateTimeString(),
                'updated_at'=> Carbon::create($cmpn['updated_at'])->toDateTimeString(),
            ];
        },$company);

        return Inertia::render('Admin/Companies/Company',[
            'company' => $cleanedDatas,
            'advertised_jobs' => $jobVacancy,
        ]);
    }

    public function generateRandomPasswordCompany($id){
        $company = Company::where('id','=',$id)->first();
        $randomPassword = Str::random(12);
        $hashedPassword = Hash::make($randomPassword);

        $company->update(['password' => $hashedPassword]);
    }

    public function setStatus($id){
        $company = Company::where('id','=',$id)->first();
        if($company && $company['is_active'] == 1){
            $company->update(['is_active' => 0]);
        }else{
            $company->update(['is_active' => 1]);
        }

        return response()->json(['status'=>'success','message'=> 'Státusz sikeresen módosítva -> '.$company['is_active']]);
    }
}

?>
<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Company;
use Illuminate\Support\Facades\Request;

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
        $jobVacancy = JobVacancy::where('created_by','=',$id)->get()->toArray();
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
}

?>
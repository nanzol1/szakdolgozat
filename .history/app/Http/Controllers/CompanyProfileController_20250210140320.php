<?php
namespace App\Http\Controllers;

use App\Models\JobCategory;
use App\Models\Employment_type;
use App\Models\Work_schedule;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Redirect;
use App\Models\Company;
use App\Models\JobVacancy;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;


class CompanyProfileController extends Controller{

    public function editCompany(Request $request)
    {
        $category = JobCategory::with('jobs_subcategories')->get();
        $categories = [];
        $employment_types = Employment_type::all();
        $work_schedules = Work_schedule::all();
        foreach($category as $item){
            $categories[$item->id] = [
                'id' => $item->id,
                'name'=> $item->name,
                'subcategories' => $item->jobs_subcategories->toArray() ?? [],
            ];
        }
        return Inertia::render('Profile/CompanyProfile/Edit', [
            'mustVerifyEmail' => $request->user() instanceof MustVerifyEmail,
            'status' => session('status'),
            'categories' => $categories,
            'employment_types' => $employment_types,
            'work_schedules' => $work_schedules,
        ]);
    }
    public function update(Request $request): RedirectResponse
    {
        $user = Company::where('email', $request->email)->first();
        $needChange = [];
        foreach($request->all() as $key => $value){
            if($user->$key !== $value){
                $needChange[$key] = $value;
            }
        }

        if($request->hasFile('profile_picture')){
            if($user->profile_pict){
                Storage::disk('public')->delete('uploads/company_profile/'.$request->user()->id.'/'.$user->profile_pict);
            }

            $path = $request->file('profile_picture');
            $needChange['profile_pict'] = $path->hashName();
        }


        
        $validated = Validator::make($needChange,[
            'company_name' => 'String|required|min:3|max:255|unique:companies',
            //'email' => 'email',
            'company_pnumber' => 'nullable|string|min:3',
            'company_address' => 'nullable|string|min:3',
            'company_desc' => 'nullable|string|min:3',
        ])->validate();


        $request->user()->update(['driverLicense' => $request->driverLicense]);


        if($request->user()->update($validated)){
            if($request->hasFile('profile_picture')){
                $path->store('uploads/company_profile/'.$request->user()->id.'/','public');
                $request->user()->update(['profile_pict' => $path->hashName()]);
            }
        }

        return Redirect::route('cprofile.edit');
    }

    public function getEmploymentsType(Request $request){
        $query = $request->employment_type;

        $employmenttype = Employment_type::where('name','LIKE','%'.$query.'%')->get();

        return response()->json([
            'employmenttype' => $employmenttype,
            'query' => $query,
        ]);

    }

    public function getWorkSchedules(Request $request){
        $query = $request->work_schedules;

        $workschedules = Work_schedule::where('name','LIKE','%'.$query.'%')->get();

        return response()->json([
            'schedules' => $workschedules,
            'query' => $query,
        ]);

    }

    public function createJobs(Request $request){
        $datas = $request->all();
        if($datas['zip_address'] && $datas['county_address']){
            $datas['address'] = $datas['zip_address'].','.$datas['county_address'];
        }

        unset($datas['zip_address']);
        unset($datas['county_address']);
        dd($datas);
        if($datas){
            $validated = Validator::make($datas,[
                'name' => 'String|required|min:3|max:255',
                'active' => 'Integer',
                'position' => 'array|required',
                'description' => 'String|required|min:3|max:255',
                'requirements' => 'String|required|min:3|max:255',
                'payment' => 'nullable|String|min:3|max:255',
                'created_by' => 'Integer',
                'maincateg_id' => 'Integer',
                'subcateg_id' => 'Integer',
                'employment_type' => 'array|required',
                'work_schedules' => 'array|required',
                'address' => 'String|required',
            ])->validate();
            dd($validated);
            
            JobVacancy::create($validated);
        }
    }
    public function destroy(Request $request): RedirectResponse
    {
        $request->validate([
            'password' => ['required', 'current_password'],
        ]);

        $user = $request->user();

        Auth::logout();

        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return Redirect::to('/');
    }

}
?>
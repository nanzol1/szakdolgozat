<?php
namespace App\Http\Controllers;

use App\Models\Application_notification;
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
use App\Facades\LogFacade as Log;


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

    public function newJobsIndex(Request $request)
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
        return Inertia::render('Profile/CompanyProfile/EditJob', [
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
            'company_name' => 'string|min:3|max:255',
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

        Log::logCompanyAction('update','Frissített az adatait!',auth('company')->user()->id);


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
        if($datas['zip_address'] && $datas['county_address'] && $datas['place_address']){
            $datas['address'] = $datas['zip_address'].','.$datas['county_address'].','.$datas['place_address'];
        }

        unset($datas['zip_address']);
        unset($datas['county_address']);
        unset($datas['place_address']);

        if($datas){
            $validated = Validator::make($datas,[
                'name' => 'String|required|min:3|max:255',
                'active' => 'Integer',
                'position' => 'array|required',
                'description' => 'String|required|min:3',
                'requirements' => 'String|required|min:3',
                'payment' => 'nullable|String|min:3|max:255',
                'created_by' => 'Integer',
                'maincateg_id' => 'Integer',
                'subcateg_id' => 'Integer',
                'employment_type' => 'array|required',
                'work_schedules' => 'array|required',
                'address' => 'String|required',
            ],[
                'name.required' => 'Kötelező mező!',
                'name.max' => 'Maximum 255 karakter lehetséges!',
                'description.required' => 'Kötelező mező!',
            ])->validate();
            $jobVacancy = JobVacancy::create($validated);
            Log::logCompanyAction('update','Meghirdetett egy új munkát!',auth('company')->user()->id);
            if($jobVacancy){
                if(isset($validated['employment_type'])){
                    $jobVacancy->employment_type()->attach($validated['employment_type']);
                }

                if(isset($validated['work_schedules'])){
                    $jobVacancy->work_schedules()->attach($validated['work_schedules']);
                }
            }

            return redirect()->back()->with('success','A munka sikeresen meghirdetésre került!');
        }
    }

    public function getNofitications(Request $request){
        $notifications = Application_notification::where('created_by', '=',$request->user()->id)
        ->get()
        ->toArray();
        return response()->json(count($notifications));
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
<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request

class CompanyProfileController extends Controller{

    public function editCompany(Request $request): Response
    {
        return Inertia::render('Profile/CompanyProfile/Edit', [
            'mustVerifyEmail' => $request->user() instanceof MustVerifyEmail,
            'status' => session('status'),
        ]);
    }
}
?>
<?php

namespace App\Http\Controllers;

use App\User;
use App\Company;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SettingsController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Client Organization
     */
    public function fetchOrg()
    {
        $company = Company::where('id', Auth::user()->company_id)->firstOrFail();
        return response()->json($company, 200);
    }

    public function getOrgUsers($id)
    {
        $users = User::where('company_id', $id)->orderBy('created_at', 'desc')->get();
        return response()->json($users, 200);
    }

    public function updateOrg(Request $request)
    {
        $company = Company::where('id', Auth::user()->company_id)->firstOrFail();
        $company->update($this->validateOrgRequest());
        return response()->json([
            'message' => 'Organization has been updated.',
        ], 200);
    }

    /**
     * Client Teams
     */
    public function deleteOrgUser($id)
    {
        $user = User::where('id', $id)->firstOrFail();

        if($user->role == 3 && $this->hasOneAdmin() == false){
            return response()->json([
                'message' => 'Unable to delete user. A team should have at least one Administrator.',
            ], 422);
        }

        $user->delete();
        return response()->json([
            'message' => 'User has been deleted',
        ], 200);
    }
    public function updateOrgUser(Request $request, $id)
    {
        $user = User::where('id', $id)->firstOrFail();
        if($this->hasOneAdmin() == false && ($user->role == 3 && $request->role != 3) ){
            return response()->json([
                'message' => 'Unable to update user. A team should have at least one Administrator.',
            ], 422);
        }
        $user->update($this->validateOrgTeamRequest());
        return response()->json([
            'message' => 'User has been updated',
        ], 200);
    }

    public function validateOrgTeamRequest()
    {
        return request()->validate([
            'name' => ['required', 'min:1', 'max:50', 'string'],
            'email' => ['sometimes', 'string', 'email', 'max:255', 'unique:users'],
            'phone' => [''],
            'role' => ['integer'],
        ]);
    }

    public function validateOrgRequest()
    {
        return request()->validate([
            'logo' => [''],
            'title' => ['required', 'min:1', 'max:50', 'string'],
            'description' => [''],
        ]);

    }

    public function hasOneAdmin()
    {
        $teamAdmins = User::where([
            'company_id' => Auth::user()->company_id,
            'role' => 3,
        ])->get();

        if($teamAdmins->count() > 1 ){
            return true;
        }
        return false;
    }
}

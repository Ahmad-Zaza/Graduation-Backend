<?php

namespace App\Http\Controllers\CompanyControllers;

use App\Http\Controllers\Controller;
use App\Models\CompanyModels\CompanyUser;
use App\Traits\QueryTrait;
use Illuminate\Http\Request;

class MainCompanyController extends Controller
{
    use QueryTrait;
    public function __construct()
    {
        $this->middleware('assign.guard:company-api')->except('');
    }

    public function getCompanyUsers($company_id)
    {
        $comp_users = CompanyUser::where('company_id', $company_id)
            ->get();

        return $this->successMessage($comp_users, '200');
    }

    public function addNewAdmin(Request $request)
    {
        $admin = CompanyUser::where('username', $request->username)
            ->where('company_id', $request->company_id)
            ->where('user_type', $request->user_type)
            ->get()
            ->first();

        if ($admin) {
            return $this->errorMessage(null, '401', 'this username is founded before !!');
        }

        $admin_comp_user = CompanyUser::create($request->all());

        return $this->successMessage($admin_comp_user, '200');
    }

    public function addNewDriver(Request $request)
    {
        $driver = CompanyUser::where('username', $request->username)
            ->where('company_id', $request->company_id)
            ->where('user_type', $request->user_type)
            ->get()
            ->first();

        if ($driver) {
            return $this->errorMessage(null, '401', 'this username is founded before !!');
        }

        $driver_comp_user = CompanyUser::create($request->all());

        return $this->successMessage($driver_comp_user, '200');
    }
}
<?php

namespace App\Http\Controllers\CompanyControllers;

use App\Http\Controllers\Controller;
use App\Models\CompanyModels\CompanyUser;
use App\Traits\QueryTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

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
        $validator = Validator::make($request->all(), [
            'company_id' => 'required|exists:companies,id',
            'first_name' => 'required|string',
            'last_name' => 'required|string',
            'username' => 'required|string|unique:company_users,username',
            "user_type" => 'required|numeric',
            "password" => 'required|confirmed',
        ]);

        if ($validator->fails()) {
            return $this->errorMessage(null, '', $validator->errors());
        }

        $admin_comp_user = CompanyUser::create($request->all());

        return $this->successMessage($admin_comp_user, '200');
    }

    public function addNewDriver(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'company_id' => 'required|exists:companies,id',
            'first_name' => 'required|string',
            'last_name' => 'required|string',
            'username' => 'required|string|unique:company_users,username',
            "user_type" => 'required|numeric',
            "password" => 'required|confirmed',
        ]);

        if ($validator->fails()) {
            return $this->errorMessage(null, '', $validator->errors());
        }

        $driver_comp_user = CompanyUser::create($request->all());

        return $this->successMessage($driver_comp_user, '200');
    }
}
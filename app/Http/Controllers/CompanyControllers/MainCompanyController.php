<?php

namespace App\Http\Controllers\CompanyControllers;

use App\Http\Controllers\Controller;
use App\Models\CompanyModels\CompanyUser;
use App\Services\CompanyServices\MainCompanyService;
use App\Traits\QueryTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class MainCompanyController extends Controller
{
    use QueryTrait;
    protected $mainCompanyService;
    public function __construct(MainCompanyService $mainCompanyService)
    {
        $this->middleware('assign.guard:company-api')->except('');
        $this->mainCompanyService = $mainCompanyService;
    }

    public function getCompanyUsers($company_id)
    {
        return $this->mainCompanyService->getCompanyUsers($company_id);
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
        return $this->mainCompanyService->addNewAdmin($request);
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
        return $this->mainCompanyService->addNewDriver($request);
    }
}
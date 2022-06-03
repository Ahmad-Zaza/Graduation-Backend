<?php

namespace App\Http\Controllers\CompanyControllers;

use App\Http\Controllers\Controller;
use App\Models\CompanyModels\Company;
use App\Models\CompanyModels\CompanyUser;
use App\Models\CompanyModels\Order;
use App\Models\CompanyModels\Subscribe;
use App\Models\CompanyModels\Truck;
use App\Services\CompanyServices\MainCompanyService;
use App\Traits\QueryTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Config;
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
            'truck_id' => 'required|exists:trucks,id'
        ]);
        if ($validator->fails()) {
            return $this->errorMessage(null, '', $validator->errors());
        }
        return $this->mainCompanyService->addNewDriver($request);
    }

    public function getBasicInfo()
    {
        $curr_user = Auth::guard('company-api')->user();
        $company_id = $curr_user->company_id;
        $info = Order::where('id', $company_id)->count();
        $info1 = Truck::where('company_id', $company_id)->count();
        $info2 = Subscribe::where('company_id', $company_id)->count();
        return $this->successMessage(['orders_count' => $info, 'trucks_count' => $info1, 'subscribes_count' => $info2], '200');
    }

    public function setFirebaseToken(Request $request, $compUserId)
    {
        $user = CompanyUser::find($compUserId);
        $user->firebasetoken = $request->firebasetoken;
        $user->save();
        return response()->json([
            'msg' => 'firebasetoken updated successfully'
        ]);
    }
}
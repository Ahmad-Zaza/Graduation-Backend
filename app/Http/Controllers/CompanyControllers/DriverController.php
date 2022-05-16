<?php

namespace App\Http\Controllers\CompanyControllers;

use App\Http\Controllers\Controller;
use App\Models\CompanyModels\CompanyUser;
use App\Services\CompanyServices\DriverService;
use App\Traits\QueryTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Gate;

class DriverController extends Controller
{
    use QueryTrait;
    protected $driverServices;

    public function __construct(DriverService $driverService)
    {
        $this->middleware('assign.guard:company-api')->except('');
        $this->driverServices = $driverService;
    }

    public function viewAllDrivers()
    {
        // gete
        $company_id = Auth::guard('company-api')->user()->company_id;
        return $this->driverServices->viewAllDrivers($company_id);
    }

    public function deleteDriver($driver_id)
    {
        $driver = CompanyUser::find($driver_id);
        if (!Gate::allows('deleteDriver', [CompanyUser::class, $driver->user_type, $driver->company_id])) {
            return $this->errorMessage(null, '403', 'This action is unauthorized');
        }
        return $this->driverServices->deleteDriver($driver_id);
    }
}
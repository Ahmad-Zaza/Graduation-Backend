<?php

namespace App\Services\CompanyServices;

use App\Models\CompanyModels\Company;
use App\Models\CompanyModels\CompanyUser;
use App\Traits\QueryTrait;
use Illuminate\Support\Facades\Config;

/**
 * Class DriverService.
 */
class DriverService
{
    use QueryTrait;

    public static function viewAllDrivers($company_id)
    {
        $per_page = request()->per_page ?? 10;

        $drivers = CompanyUser::where('company_id', $company_id)
            ->where('user_type', Config::get('constants.company.users.driver_type'))
            ->paginate($per_page);

        return (new static)->successMessage($drivers, '200');
    }

    public static function deleteDriver($driver_id)
    {
        $driver = CompanyUser::find($driver_id);

        $driver->delete();

        return response()->json('Driver has been deleted');
    }
}
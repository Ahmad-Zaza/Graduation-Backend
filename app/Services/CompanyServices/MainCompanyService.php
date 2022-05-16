<?php

namespace App\Services\CompanyServices;

use App\Models\CompanyModels\CompanyUser;
use App\Traits\QueryTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

/**
 * Class MainCompanyService.
 */
class MainCompanyService
{
    use QueryTrait;
    public static function getCompanyUsers($company_id)
    {
        $comp_users = CompanyUser::where('company_id', $company_id)
            ->get();

        return (new static)->successMessage($comp_users, '200');
    }

    public static function addNewAdmin(Request $request)
    {
        $admin_comp_user = CompanyUser::create($request->all());
        return (new static)->successMessage($admin_comp_user, '200');
    }

    public static function addNewDriver(Request $request)
    {
        $driver_comp_user = CompanyUser::create($request->all());
        return (new static)->successMessage($driver_comp_user, '200');
    }
}
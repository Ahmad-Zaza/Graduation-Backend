<?php

namespace App\Policies;

use App\Models\CompanyModels\CompanyUser;
use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Support\Facades\Config;

class CompanyUserPolicy
{
    use HandlesAuthorization;

    public function viewAny(CompanyUser $companyUser)
    {
        //
    }


    public function view(CompanyUser $companyUser, CompanyUser $companyUser1)
    {
        //
    }

    public function deleteDriver(CompanyUser $companyUser, $driver_user_type_id, $driver_company_id)
    {
        return $companyUser->company_id == $driver_company_id
            && $companyUser->user_type == Config::get('constants.company.users.admin_type')
            && $driver_user_type_id == Config::get('constants.company.users.driver_type');
    }
}
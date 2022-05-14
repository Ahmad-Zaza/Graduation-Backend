<?php

namespace App\Policies;

use App\Models\CompanyModels\CompanyUser;
use App\Models\CompanyModels\Subscribe;
use Illuminate\Auth\Access\HandlesAuthorization;

class SubscribePolicy
{
    use HandlesAuthorization;

    public function viewAllRetailDealers(CompanyUser $companyUser, $company_id)
    {
        return $companyUser->company_id == $company_id;
    }

    public function viewAny(CompanyUser $companyUser)
    {
        //
    }

    public function view(CompanyUser $companyUser, Subscribe $subscribe)
    {
        //
    }


    public function create(CompanyUser $companyUser)
    {
        //
    }

    public function update(CompanyUser $companyUser, Subscribe $subscribe)
    {
        //
    }

    public function delete(CompanyUser $companyUser, Subscribe $subscribe)
    {
        //
    }

    public function restore(CompanyUser $companyUser, Subscribe $subscribe)
    {
        //
    }

    public function forceDelete(CompanyUser $companyUser, Subscribe $subscribe)
    {
        //
    }
}
<?php

namespace App\Policies;

use App\Models\CompanyModels\CompanyUser;
use App\Models\CompanyModels\Product;
use App\Models\RetailDealersModel\RetailDealer;
use Illuminate\Auth\Access\HandlesAuthorization;

class ProductPolicy
{
    use HandlesAuthorization;

    public function viewAny(CompanyUser $companyUser, $company_id)
    {
        return $companyUser->user_type == 1 && $companyUser->company_id == $company_id;
    }

    public function view(CompanyUser $companyUser, $company_id)
    {
        return $companyUser->user_type == 1 && $companyUser->company_id == $company_id;
    }

    public function create(CompanyUser $companyUser, $company_id)
    {
        return $companyUser->user_type == 1 && $companyUser->company_id == $company_id;
    }


    public function update(CompanyUser $companyUser, $company_id)
    {
        return $companyUser->user_type == 1 && $companyUser->company_id == $company_id;
    }

    public function viewProducts(RetailDealer $retailDealer, $company_id)
    {
        return  $retailDealer->company_id == $company_id;
    }


    public function delete(CompanyUser $companyUser, Product $product)
    {
        //
    }


    public function restore(CompanyUser $companyUser, Product $product)
    {
        //
    }


    public function forceDelete(CompanyUser $companyUser, Product $product)
    {
        //
    }
}
<?php

namespace App\Policies;

use App\Models\CompanyModels\CompanyUser;
use App\Models\CompanyModels\ProductType as ProductType;
use Illuminate\Auth\Access\HandlesAuthorization;

class ProductTypePolicy
{
    use HandlesAuthorization;

    public function viewAny(CompanyUser $companyUser)
    {
        //
    }

    public function view(CompanyUser $companyUser, ProductType $productType)
    {
        //
    }

    public function create(CompanyUser $companyUser)
    {
        //
    }

    public function update(CompanyUser $companyUser, ProductType $productType)
    {
        //
    }

    public function delete(CompanyUser $companyUser, ProductType $productType)
    {
        //
    }

    public function restore(CompanyUser $companyUser, ProductType $productType)
    {
        //
    }

    public function forceDelete(CompanyUser $companyUser, ProductType $productType)
    {
        //
    }
}
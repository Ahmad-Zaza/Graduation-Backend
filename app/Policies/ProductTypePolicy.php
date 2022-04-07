<?php

namespace App\Policies;

use App\Models\CompanyModels\CompanyUser;
use App\Models\ProductType;
use Illuminate\Auth\Access\HandlesAuthorization;

class ProductTypePolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any models.
     *
     * @param  \App\Models\CompanyModels\CompanyUser  $companyUser
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function viewAny(CompanyUser $companyUser)
    {
        //
    }

    /**
     * Determine whether the user can view the model.
     *
     * @param  \App\Models\CompanyModels\CompanyUser  $companyUser
     * @param  \App\Models\ProductType  $productType
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function view(CompanyUser $companyUser, ProductType $productType)
    {
        //
    }

    /**
     * Determine whether the user can create models.
     *
     * @param  \App\Models\CompanyModels\CompanyUser  $companyUser
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function create(CompanyUser $companyUser)
    {
        //
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param  \App\Models\CompanyModels\CompanyUser  $companyUser
     * @param  \App\Models\ProductType  $productType
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function update(CompanyUser $companyUser, ProductType $productType)
    {
        //
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param  \App\Models\CompanyModels\CompanyUser  $companyUser
     * @param  \App\Models\ProductType  $productType
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function delete(CompanyUser $companyUser, ProductType $productType)
    {
        //
    }

    /**
     * Determine whether the user can restore the model.
     *
     * @param  \App\Models\CompanyModels\CompanyUser  $companyUser
     * @param  \App\Models\ProductType  $productType
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function restore(CompanyUser $companyUser, ProductType $productType)
    {
        //
    }

    /**
     * Determine whether the user can permanently delete the model.
     *
     * @param  \App\Models\CompanyModels\CompanyUser  $companyUser
     * @param  \App\Models\ProductType  $productType
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function forceDelete(CompanyUser $companyUser, ProductType $productType)
    {
        //
    }
}

<?php

namespace App\Policies;

use App\Models\CompanyModels\Category;
use App\Models\CompanyModels\CompanyUser;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class CategoryPolicy
{
    use HandlesAuthorization;

    public function viewAny(?CompanyUser $user)
    {
        return true;
        // return $user->company_id == $company_id;
    }

    public function index(CompanyUser $user, $company_id)
    {
        return $user->company_id == $company_id;
    }


    public function view(CompanyUser $user,  $company_id)
    {
        return $user->user_type == 1 && $user->company_id == $company_id;
    }

    public function create(CompanyUser $user, $company_id)
    {
        return $user->user_type == 1 && $user->company_id == $company_id;
    }


    public function update(CompanyUser $user, $company_id)
    {
        return $user->user_type == 1 && $user->company_id == $company_id;
    }


    public function delete(CompanyUser $user, Category $category)
    {
        //
    }

    public function restore(CompanyUser $user, Category $category)
    {
        //
    }


    public function forceDelete(CompanyUser $user, Category $category)
    {
        //
    }
}
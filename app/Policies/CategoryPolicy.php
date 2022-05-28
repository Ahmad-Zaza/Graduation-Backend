<?php

namespace App\Policies;

use App\Models\CompanyModels\Category;
use App\Models\CompanyModels\CompanyUser;
use App\Models\CompanyModels\Subscribe;
use App\Models\RetailDealersModel\RetailDealer;
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

    public function viewCategoryForRetailDealer(RetailDealer $retailDealer, $company_id)
    {
        $subscribe = Subscribe::where('company_id', $company_id)
            ->where('retail_dealer_id', $retailDealer->id)
            ->count();

        return $subscribe > 0;
    }

    public function viewProductByCategoryForRetailDealer(RetailDealer $retailDealer, $category_id)
    {
        $category = Category::find($category_id);
        $subscribe = Subscribe::where('company_id', $category->company_id)
            ->where('retail_dealer_id', $retailDealer->id)
            ->count();

        return $subscribe > 0;
    }
}
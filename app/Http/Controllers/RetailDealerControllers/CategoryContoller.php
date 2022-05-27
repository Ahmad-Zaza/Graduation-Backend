<?php

namespace App\Http\Controllers\RetailDealerControllers;

use App\Http\Controllers\Controller;
use App\Models\CompanyModels\Category;
use App\Models\CompanyModels\Product;
use App\Services\RetailDealerServices\CategoryService;
use App\Traits\QueryTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;

class CategoryContoller extends Controller
{
    use QueryTrait;
    protected $categoryService;
    public function __construct(CategoryService $categoryService)
    {
        $this->middleware('assign.guard:retail-dealer-api');
        $this->categoryService = $categoryService;
    }

    public function viewCompanyCategories($company_id)
    {
        //Gate
        if (!Gate::forUser(Auth::guard('retail-dealer-api')->user())->allows('viewCategoryForRetailDealer', [Category::class, $company_id])) {
            return $this->errorMessage(null, '403', 'This action is unauthorized');
        }
        return $this->categoryService->viewCompanyCategories($company_id);
    }

    public function viewProductsByCategoryId($category_id)
    {

        if (!Gate::forUser(Auth::guard('retail-dealer-api')->user())->allows('viewProductByCategoryForRetailDealer', [Category::class, $category_id])) {
            return $this->errorMessage(null, '403', 'This action is unauthorized');
        }

        return $this->categoryService->viewProductsByCategoryId($category_id);
    }
}
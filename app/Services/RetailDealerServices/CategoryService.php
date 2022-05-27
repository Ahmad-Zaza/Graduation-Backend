<?php

namespace App\Services\RetailDealerServices;

use App\Models\CompanyModels\Category;
use App\Models\CompanyModels\Product;
use App\Traits\QueryTrait;

/**
 * Class CategoryService.
 */
class CategoryService
{
    use QueryTrait;

    public static function viewCompanyCategories($company_id)
    {
        $per_page = request()->per_page ?? 10;
        $categories = Category::where('company_id', '=', $company_id)
            ->paginate($per_page);

        return (new static)->successMessage($categories, '200');
    }

    public static function viewProductsByCategoryId($category_id)
    {
        $per_page = request()->per_page ?? 10;
        $products = Product::with('productType')
            ->where('category_id', $category_id)
            ->paginate($per_page);

        return (new static)->successMessage($products, '200');
    }
}
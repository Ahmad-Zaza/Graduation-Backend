<?php

namespace App\Services\RetailDealerServices;

use App\Models\CompanyModels\Company;
use App\Models\RetailDealersModel\RetailDealer;
use App\Traits\QueryTrait;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

/**
 * Class OrderService.
 */
class OrderService
{
    use QueryTrait;

    public static function retailDealerCompanies()
    {
        $per_page = request()->per_page ?? 10;
        $companies = DB::table('companies')
            ->join('subscribes', function ($join) {
                $retail_dealer = Auth::guard('retail-dealer-api')->user();
                $join->on('subscribes.company_id', '=', 'companies.id')
                    ->where('subscribes.retail_dealer_id', '=', $retail_dealer->id);
            })
            ->select('companies.*')
            ->paginate($per_page);

        return (new static)->successMessage($companies, '200');
    }

    public static function companiesQuerySearch()
    {
        $searchText = request()->searchText;
        $companies = DB::table('companies')
            ->join('subscribes', function ($join) {
                $retail_dealer = Auth::guard('retail-dealer-api')->user();
                $join->on('subscribes.company_id', '=', 'companies.id')
                    ->where('subscribes.retail_dealer_id', '=', $retail_dealer->id);
            })
            ->where('companies.name', 'like', '%' . $searchText . '%')
            ->limit(5)
            ->get();
        return (new static)->successMessage($companies, '200');
    }

    public static function companyProducts($company_id)
    {
        $per_page = request()->per_page ?? 10;
        $products = DB::table('products')
            ->join('product_types', function ($join) use ($company_id) {
                $join->on('product_types.id', '=', 'products.product_type_id')
                    ->where('product_types.company_id', '=', $company_id);
            })
            ->select('products.*')
            ->paginate($per_page);
        return (new static)->successMessage($products, '200');
    }
}
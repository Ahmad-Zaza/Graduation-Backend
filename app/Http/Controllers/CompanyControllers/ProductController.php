<?php

namespace App\Http\Controllers\CompanyControllers;

use App\Http\Controllers\Controller;
use App\Models\CompanyModels\Product;
use App\Traits\QueryTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ProductController extends Controller
{
    use QueryTrait;
    public function __construct()
    {
        $this->middleware('assign.guard:company-api')->except('');
    }

    public function addNewProduct(Request $request, $category_id, $product_type)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|unique:products,name',
            'price' => 'required|numeric',
        ]);

        if ($validator->fails()) {
            return $this->errorMessage(null, '404', $validator->errors());
        }

        $product = Product::create([
            'name' => $request->name,
            'price' => $request->price,
            'reorder_point' => $request->reorder_point,
            'description' => $request->description,
            'category_id' => $request->categroy_id,
            'product_type_id' => $request->product_type_id
        ]);

        return $this->successMessage($product, '200');
    }

    public function viewProduct(Product $product)
    {
        return $this->successMessage($product, '200');
    }

    public function updateProduct(Request $request, $product_id)
    {
        $product = Product::find($product_id);
    }
}
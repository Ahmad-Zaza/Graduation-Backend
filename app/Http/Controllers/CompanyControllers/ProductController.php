<?php

namespace App\Http\Controllers\CompanyControllers;

use App\Http\Controllers\Controller;
use App\Models\CompanyModels\Category;
use App\Models\CompanyModels\Company;
use App\Models\CompanyModels\Product;
use App\Models\CompanyModels\ProductType;
use App\Traits\QueryTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Validator;

class ProductController extends Controller
{
    use QueryTrait;
    public function __construct()
    {
        $this->middleware('assign.guard:company-api')->except('');
    }

    public function viewAllProducts($company_id)
    {
        // check authorization
        $check = Gate::allows('viewAny', [Product::class, $company_id]);
        if ($check == false) {
            return $this->errorMessage(null, '403', 'This action is unauthorized');
        }

        $per_page = request()->per_page ?? 10;

        $products = DB::table('products')
            ->join('categories', 'products.category_id', '=', 'categories.id')
            ->where('categories.company_id', '=', $company_id)
            ->select('products.id', 'products.name as prod_name', 'products.price', 'categories.name')
            ->paginate($per_page);

        if ($products) {
            return $this->successMessage($products, '200');
        } else {
            return $this->errorMessage(null, '414', 'No products founded!!');
        }
    }

    public function viewAllProductByCategoryId($category_id)
    {
        $category = Category::find($category_id);

        $per_page = request()->per_page ?? 10;

        $products = DB::table('products')
            ->join('categories', function ($join) use ($category_id) {
                $join->on('products.category_id', '=', 'categories.id');
                $join->where('products.category_id', '=', $category_id);
            })
            ->where('categories.company_id', '=', $category->company_id)
            ->select('products.id', 'products.name as prod_name', 'products.price', 'categories.name')
            ->paginate($per_page);

        if ($products) {
            return $this->successMessage($products, '200');
        } else {
            return $this->errorMessage(null, '414', 'No products founded!!');
        }
    }

    public function addNewProduct(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|unique:products,name',
            'price' => 'required|numeric',
            'category_id' => 'required|exists:categories,id',
            'product_type_id' => 'required|exists:product_types,id'
        ]);

        if ($validator->fails()) {
            return $this->errorMessage(null, '404', $validator->errors());
        }
        $category = Category::find($request->category_id);
        // check authorization
        $check = Gate::allows('create', [Product::class, $category->company_id]);
        if ($check == false) {
            return $this->errorMessage(null, '403', 'This action is unauthorized');
        }

        $product = Product::create([
            'name' => $request->name,
            'price' => $request->price,
            'reorder_point' => $request->reorder_point,
            'description' => $request->description,
            'category_id' => $request->category_id,
            'product_type_id' => $request->product_type_id
        ]);

        $product['category'] = Category::find($product->category_id);
        $product['product_type'] = ProductType::find($product->product_type_id);

        return $this->successMessage($product, '200');
    }

    public function viewProduct($product_id)
    {
        $product = Product::find($product_id);
        $category = Category::find($product->category_id);
        // check authorization
        $check = Gate::allows('view', [Product::class, $category->company_id]);
        if ($check == false) {
            return $this->errorMessage(null, '403', 'This action is unauthorized');
        }
        return $this->successMessage($product, '200');
    }

    public function updateProduct(Request $request, $product_id)
    {
        $product = Product::find($product_id);

        $category = Category::find($product->category_id);
        // check authorization
        $check = Gate::allows('update', [Product::class, $category->company_id]);
        if ($check == false) {
            return $this->errorMessage(null, '403', 'This action is unauthorized');
        }

        $validator = Validator::make($request->all(), [
            'name' => 'required|string|unique:products,name,' . $product->id . ' ,id',
            'price' => 'required|numeric',
            'category_id' => 'required|exists:categories,id',
            'product_type_id' => 'required|exists:product_types,id'
        ]);
        if ($validator->fails()) {
            return $this->errorMessage(null, '', $validator->errors());
        }
        $product->update($request->all());

        return $this->successMessage($product, '200');
    }

    public function deleteProduct()
    {
    }


    // product types services ...
    public function addNewProductType(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string',
            'company_id' => 'required|exists:companies,id'
        ]);
        if ($validator->fails()) {
            return $this->errorMessage(null, '', $validator->errors());
        }

        $product_type = ProductType::create([
            'name' => $request->name,
            'company_id' => $request->company_id,
            'description' => $request->description
        ]);

        return $this->successMessage($product_type, '200');
    }

    public function updateProductType(Request $request, $product_type_id)
    {
        $product_type = ProductType::find($product_type_id);

        $validator = Validator::make($request->all(), [
            'name' => 'required|string',
            'company_id' => 'required|exists:companies,id'
        ]);
        if ($validator->fails()) {
            return $this->errorMessage(null, '', $validator->errors());
        }

        $product_type->update($request->all());

        return $this->successMessage($product_type, '200');
    }

    public function viewProductType($product_type_id)
    {
        $product_type = ProductType::find($product_type_id);
        return $this->successMessage($product_type, '200');
    }

    public function viewAllProductTypes($company_id)
    {
        $per_page = request()->per_page ?? 10;
        $product_types = ProductType::where('company_id', $company_id)
            ->paginate($per_page);

        return $this->successMessage($product_types, '200');
    }

    public function ProductQuerySearch()
    {
        $searchText = request()->searchText;

        //   (SELECT products.name, products.category_id as cat_id
        //     FROM products where products.category_id IN
        //     (SELECT categories.id as category_id
        //     FROM categories
        //      WHERE categories.company_id = 2)
        $company_id = Auth::guard('company-api')->user()->company_id;
        $products = DB::table('products')
            ->join('categories', 'products.category_id', '=', 'categories.id')
            ->where('categories.company_id', '=', $company_id)
            ->where('products.name', 'like', '%' . $searchText . '%')
            ->select('products.id', 'products.name as prod_name', 'products.price', 'categories.name')
            ->limit(5)
            ->get();


        return $this->successMessage($products, '200');
    }
}
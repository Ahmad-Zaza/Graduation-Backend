<?php

namespace App\Http\Controllers\CompanyControllers;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Traits\QueryTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class CategoryController extends Controller
{
    use QueryTrait;
    public function __construct()
    {
        $this->middleware('assign.guard:company-api')->except('');
    }

    public function index()
    {
        $per_page = request()->per_page ?? 10;

        $categories = Category::all()->paginate($per_page);
        if ($categories) {
            return $this->successMessage($categories, '200');
        } else {
            return $this->errorMessage(null, '414', 'No Categories Founded!!');
        }
    }


    public function create()
    {
        //
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string',
        ]);

        if ($validator->fails()) {
            return $this->errorMessage(null, '404', $validator->errors());
        }

        $category = Category::create($request->all());

        return $this->successMessage($category, '200');
    }


    public function show($id)
    {
        $category = Category::find($id);
        if ($category) {
            return $this->successMessage($category, '200');
        } else {
            return $this->errorMessage(null, '404', 'No Category Founded!!');
        }
    }


    public function edit($id)
    {
    }


    public function update(Request $request, $id)
    {
        $category = Category::find($id);
        $validator = Validator::make($request->all(), [
            'name' => 'required|string',
        ]);

        if ($validator->fails()) {
            return $this->errorMessage(null, '404', $validator->errors());
        }

        $category->update($request->all());

        return $this->successMessage($category, '200');
    }


    public function destroy($id)
    {
        //
    }
}
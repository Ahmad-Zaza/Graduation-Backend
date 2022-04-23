<?php

namespace App\Http\Controllers\CompanyControllers;

use App\Http\Controllers\Controller;

use App\Models\CompanyModels\Category;
use App\Traits\QueryTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Validator;

class CategoryController extends Controller
{

    use QueryTrait;

    public function __construct()
    {
        $this->middleware('assign.guard:company-api')->except('');
    }

    public function index($company_id)
    {
        // check authorization
        $check = Gate::allows('index', [Category::class, $company_id]);
        if ($check == false) {
            return $this->errorMessage(null, '403', 'This action is unauthorized');
        }
        $per_page = request()->per_page ?? 10;

        $categories = Category::where('company_id', $company_id)
            ->paginate($per_page);

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
        // check authorization
        $check = Gate::allows('create', [Category::class, $request->company_id]);
        if ($check == false) {
            return $this->errorMessage(null, '403', 'This action is unauthorized');
        }
        $validator = Validator::make($request->all(), [
            'name' => 'required|string',
            'company_id' => 'required|exists:companies,id'
        ]);

        if ($validator->fails()) {
            return $this->errorMessage(null, '404', $validator->errors());
        }

        $category = Category::create($request->all());

        // $category->company()->syncWithoutDetaching($request->company_id);

        $category['company'] = $category->company();

        return $this->successMessage($category, '200');
    }


    public function show($id)
    {
        // check authorization
        $check = Gate::allows('view', Category::class);
        if ($check == false) {
            return $this->errorMessage(null, '403', 'This action is unauthorized');
        }

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

        // check authorization
        $check = Gate::allows('update', [Category::class, $category->company_id]);
        if ($check == false) {
            return $this->errorMessage(null, '403', 'This action is unauthorized');
        }

        $validator = Validator::make($request->all(), [
            'name' => 'required|string',
            'company_id' => 'required|exists:companies,id'
        ]);

        if ($validator->fails()) {
            return $this->errorMessage(null, '404', $validator->errors());
        }

        $category->update($request->all());

        return $this->successMessage($category, '200');
    }


    public function destroy($id)
    {
        $category = Category::find($id);
        $category->delete();
        return $this->response(null, '200', 'category deleted successfully');
    }

    public function categoryQuerySearch()
    {
        $company_id = Auth::guard('company-api')->user()->company_id;
        $searchText = request()->searchText;
        $categories = Category::where('company_id', $company_id)
            ->where('name', 'LIKE', '%' . $searchText . '%')
            ->limit(5)
            ->get();
        return $this->successMessage($categories, '200');
    }
}
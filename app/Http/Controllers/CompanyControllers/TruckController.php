<?php

namespace App\Http\Controllers\CompanyControllers;

use App\Http\Controllers\Controller;
use App\Models\CompanyModels\Truck;
use App\Traits\QueryTrait;
use Illuminate\Auth\Events\Validated;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class TruckController extends Controller
{
    use QueryTrait;

    public function __construct()
    {
        return $this->middleware('assign.guard:company-api');
    }

    public function addNewTruck(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'truck_number' => 'required|unique:trucks,truck_number',
            'company_id' => 'required|exists:companies,id'
        ]);
        if ($validator->fails()) {
            return $this->errorMessage('null', '404', $validator->errors());
        }

        $truck = Truck::create([
            'truck_number' => $request->truck_number,
            'description' => $request->description,
            'company_id' => $request->company_id
        ]);
        if ($truck)
            return $this->successMessage($truck, '200');
    }

    public function updateTruck(Request $request, $truck_id)
    {
        $truck = Truck::find($truck_id);
        $validator = Validator::make($request->all(), [
            'truck_number' => 'required|unique:trucks,truck_number',
            'company_id' => 'required|exists:companies,id'
        ]);
        if ($validator->fails()) {
            return $this->errorMessage('null', '404', $validator->errors());
        }

        $truck->update($request->all());

        return $this->successMessage($truck, '200');
    }

    public function viewTruck($truck_id)
    {
        $truck = Truck::find($truck_id);
        return $this->successMessage($truck, '200');
    }

    public function viewAllTrucks($company_id)
    {
        $per_page = request()->per_page ?? 10;
        $trucks = Truck::where('company_id', $company_id)
            ->paginate($per_page);

        return $this->successMessage($trucks, '200');
    }

    public function truckQuerySearch()
    {
        $company_id = Auth::guard('company-api')->user()->company_id;
        $searchText = request()->searchText;
        $trucks = Truck::where('company_id', $company_id)
            ->where('truck_number', 'LIKE', '%' . $searchText . '%')
            ->limit(5)
            ->get();
        return $this->successMessage($trucks, '200');
    }
}
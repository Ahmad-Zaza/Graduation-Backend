<?php

namespace App\Http\Controllers\RetailDealerControllers;

use App\Http\Controllers\Controller;
use App\Models\RetailDealersModel\RetailDealer;
use App\Services\RetailDealerServices\MainService;
use App\Traits\QueryTrait;
use Illuminate\Http\Request;

class MainController extends Controller
{
    use QueryTrait;
    protected $mainService;
    public function __construct(MainService $mainService)
    {
        $this->middleware('assign.guard:retail-dealer-api');
        $this->mainService = $mainService;
    }

    public function viewMainInfo()
    {
        return $this->mainService->viewMainInfo();
    }

    public function setFirebaseToken(Request $request, $retDealId)
    {
        $user = RetailDealer::find($retDealId);
        $user->firebasetoken = $request->firebasetoken;
        $user->save();
        return response()->json([
            'msg' => 'firebasetoken updated successfully'
        ]);
    }
}
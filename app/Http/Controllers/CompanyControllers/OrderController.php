<?php

namespace App\Http\Controllers\CompanyControllers;

use App\Http\Controllers\Controller;
use App\Services\CompanyServices\OrderService;
use App\Traits\QueryTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class OrderController extends Controller
{
    use QueryTrait;
    protected $orderService;
    public function __construct(OrderService $orderService)
    {
        $this->middleware('assign.guard:company-api');
        $this->orderService = $orderService;
    }

    public function viewAllOrders()
    {
        $company_id = Auth::guard('company-api')->user()->company_id;
        return $this->orderService->viewAllOrders($company_id);
    }
}
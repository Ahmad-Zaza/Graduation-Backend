<?php

namespace App\Http\Controllers\RetailDealerControllers;

use App\Http\Controllers\Controller;
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
}
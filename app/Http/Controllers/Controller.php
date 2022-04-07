<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Facades\Auth;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    public function authorize($ability, $arguments = [])
    {
        if (Auth::guard('company-api')->check()) {
            Auth::shouldUse('company-api');
        }

        $this->baseAuthorize($ability, $arguments);
    }
}
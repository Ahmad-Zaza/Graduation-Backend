<?php

namespace App\Traits;

use App\Models\DoctorModels\Doctor;
use Illuminate\Support\Facades\Auth;

trait QueryTrait
{
    public function doPagination(array $params, $query)
    {
        $query->orderBy($params['order_by'] ?? 'id', $params['order_sort'] ?? 'desc');
        return $query->paginate($params['per_page'] ?? 10);
    }

    public function checkUserToken($auth_user, $user)
    {
        if ($auth_user == $user) {
            return true;
        }
        return false;
    }

    public function errorMessage($data, $code, $msg = '')
    {
        return response()->json([
            'resutl' => $data,
            'code' => $code,
            'msg' => $msg
        ], 404);
    }

    public function successMessage($data, $code)
    {
        return response()->json([
            'result' => $data,
            'code' => $code,
        ], 200);
    }

    public function AdminsuccessMessage($data)
    {
        return response()->json([
            'result' => $data,
        ], 200);
    }


}

<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\notifications;
use App\Traits\QueryTrait;
use Illuminate\Http\Request;

class NotificationsController extends Controller
{
    use QueryTrait;
    public function __construct()
    {
    }

    public function getUserNotification(Request $request)
    {
        $per_page = $per_page ?? 10;
        $notifications = notifications::where('user_id', '=', $request->user_id)
            ->where('type', '=', $request->type)
            ->paginate($per_page);
        return $this->successMessage($notifications, '200');
    }
}

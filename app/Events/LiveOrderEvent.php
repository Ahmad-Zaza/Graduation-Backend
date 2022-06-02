<?php

namespace App\Events;

use App\Models\CompanyModels\Order;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class LiveOrderEvent
{
    use Dispatchable, InteractsWithSockets, SerializesModels;
    public $order;

    public function __construct($order)
    {
        $this->order = $order;
    }


    public function broadcastOn()
    {
        return new PrivateChannel('order.' . $this->order->id);
    }
}
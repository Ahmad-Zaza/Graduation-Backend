<?php

namespace App\Models\CompanyModels;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderDetail extends Model
{
    use HasFactory;

    protected $fillable = [
        'id',
        'order_id',
        'product_id',
        'count'
    ];

    public function order()
    {
        return $this->belongsTo(Order::class);
    }
}
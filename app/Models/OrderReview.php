<?php

namespace App\Models;

use App\Models\CompanyModels\Order;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderReview extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_id',
        'retail_dealer_id',
        'company_id',
        'rating',
        'content'
    ];

    public function order(){
        return $this->belongsTo(Order::class);
    }

}

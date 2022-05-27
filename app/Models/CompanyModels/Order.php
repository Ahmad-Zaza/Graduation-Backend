<?php

namespace App\Models\CompanyModels;

use App\Models\RetailDealersModel\RetailDealer;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;

class Order extends Model
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'id',
        'company_id',
        'retail_dealer_id',
        'company_user_id',
        'status',
        'total_price'
    ];

    public function company()
    {
        return $this->belongsTo(Company::class);
    }

    public function retailDealer()
    {
        return $this->belongsTo(RetailDealer::class);
    }

    public function orderDetails()
    {
        return $this->hasMany(OrderDetail::class);
    }

    public function companyUser()
    {
        return $this->belongsTo(CompanyUser::class);
    }
}
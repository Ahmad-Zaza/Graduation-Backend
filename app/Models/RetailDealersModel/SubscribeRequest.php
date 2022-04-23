<?php

namespace App\Models\RetailDealersModel;

use App\Models\CompanyModels\Company;
use App\Models\RetailDealersModel\RetailDealer;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SubscribeRequest extends Model
{
    use HasFactory;
    protected $fillable = [
        'id',
        'company_id',
        'status',
        'retail_dealer_id'
    ];

    public function retailDealer()
    {
        return $this->belongsTo(RetailDealer::class);
    }

    public function company()
    {
        return $this->belongsTo(Company::class);
    }
}
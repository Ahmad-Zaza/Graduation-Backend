<?php

namespace App\Models\CompanyModels;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Subscribe extends Model
{
    use HasFactory;

    protected $fillable = [
        'id',
        'company_id',
        'retail_dealer_id'
    ];

    public function company()
    {
        return $this->belongsTo(Company::class);
    }
}
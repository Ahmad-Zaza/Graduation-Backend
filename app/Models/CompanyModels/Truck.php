<?php

namespace App\Models\CompanyModels;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;

class Truck extends Model
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'truck_number',
        'company_id',
        'description'
    ];

    public function company()
    {
        return $this->belongsTo(Company::class);
    }

    public function companyUser()
    {
        return $this->belongsTo(CompanyUser::class);
    }
}
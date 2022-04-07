<?php

namespace App\Models\CompanyModels;

use App\Models\CompanyModels\CompanyUser;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;

class Company extends Model
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'description'
    ];

    public function company_users()
    {
        return $this->hasMany(CompanyUser::class);
    }

    public function productTypes()
    {
        return $this->hasMan(ProductType::class);
    }
}
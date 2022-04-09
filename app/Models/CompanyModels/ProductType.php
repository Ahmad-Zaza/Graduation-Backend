<?php

namespace App\Models\CompanyModels;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;

class ProductType extends Model
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'description',
        'company_id'
    ];

    public function products()
    {
        return $this->hasMany(Product::class);
    }

    public function company()
    {
        return $this->belongsTo(Company::class);
    }
}
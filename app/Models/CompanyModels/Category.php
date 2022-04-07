<?php

namespace App\Models\CompanyModels;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;

class Category extends Model
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'company_id',
        'description'
    ];

    public function company()
    {
        return $this->belongsTo(Company::class);
    }
}
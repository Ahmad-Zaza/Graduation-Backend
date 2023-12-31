<?php

namespace App\Models\CompanyModels;


use App\Models\CompanyModels\Company;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Tymon\JWTAuth\Contracts\JWTSubject;

class CompanyUser extends Authenticatable implements JWTSubject
{
    use HasFactory, Notifiable;
    protected $guard = 'company-api';
    protected $table = 'company_users';

    protected $fillable = [
        'first_name',
        'last_name',
        'username',
        'company_id',
        'email',
        'password',
        'phone_number',
        'user_type',
        'photo_id',
        'firebasetoken',
        'truck_id'
    ];

    public function company()
    {
        return $this->belongsTo(Company::class);
    }

    public function order()
    {
        return $this->hasOne(Order::class);
    }

    public function truck()
    {
        return $this->hasOne(Truck::class);
    }

    public function setPasswordAttribute($password)
    {
        $this->attributes['password'] = bcrypt($password);
    }

    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    public function getJWTCustomClaims()
    {
        return [];
    }
}
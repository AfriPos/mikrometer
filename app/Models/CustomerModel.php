<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Model;

class CustomerModel extends Authenticatable
{
    use HasFactory;
    protected $table = 'customers';
    protected $fillable = [
        'status',
        'name',
        'email',
        'phone',
        'phone',
        'portal_login',
        'portal_password',
        'service_type',
        'category',
        'billing_email',
        'mpesa_phone',
        'dob',
        'id_number',
        'street',
        'zip_code',
        'city',
        'geo_data',
    ];
}

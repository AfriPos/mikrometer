<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CustomerSubscriptionModel extends Model
{
    use HasFactory;
    protected $table = 'customer_subscription';
    protected $fillable = [
        'profile_name',
        'start_date',
        'end_date',
        'invoiced_till',
        'pppoe_password',
        'pppoe_login',
        'status',
        'local_address',
        'remote_address',
        'customer_id',
    ];
}

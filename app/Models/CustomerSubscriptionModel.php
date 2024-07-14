<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CustomerSubscriptionModel extends Model
{
    use HasFactory;
    protected $table = 'customer_subscription';
    protected $fillable = [
        'service',
        'start_date',
        'end_date',
        'invoiced_till',
        'pppoe_password',
        'pppoe_login',
        'status',
        'service_price',
        'ipaddress',
        'nas_id',
    ];

    public function pppoeservice()
    {
        return $this->belongsTo(PPPoEService::class, 'pppoe_id');
    }

    public function customer()
    {
        return $this->belongsTo(CustomerModel::class);
    }
    public function router()
    {
        return $this->belongsTo(RouterCredential::class);
    }
}

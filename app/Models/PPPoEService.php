<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PPPoEService extends Model
{
    use HasFactory;

    protected $table = 'pppoe_services';
    public $timestamps = false;
    protected $fillable = [
        'service_name',
        'service_duration',
        'duration_unit',
        'service_price',
    ];

}

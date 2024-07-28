<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class hotspotModel extends Model
{
    use HasFactory;
    protected $table = 'hotspot_plans';
    protected $fillable = [
        'plan_name',
        'plan_price',
        'data_limit',
        'data_limit_unit',
        'validity',
        'validity_unit',
       'speed_limit',
       'speed_limit_unit',
       'simultaneous_use',
    ];
}

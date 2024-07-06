<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class radusergroup extends Model
{
    use HasFactory;

    protected $table = 'radusergroup';
    public $timestamps = false;

    protected $fillable = [
        'username',
        'groupname',
        'priority',
        'service_price',
        'service_duration',
        'duration_unit',
    ];
}

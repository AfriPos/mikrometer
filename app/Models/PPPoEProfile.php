<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PPPoEProfile extends Model
{
    use HasFactory;
    protected $table = 'pppoe_profiles';
    public $timestamps = false; 
    protected $fillable = [
        'profile_name',
        'local_address',
        'remote_address',
        'rate_limit',
    ];
}

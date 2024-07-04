<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProfileModel extends Model
{
    use HasFactory;
    protected $table = 'pppoe_profiles';
    protected $fillable = [
        'profile_name',
        'rate_limit',
    ];
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PoolModel extends Model
{
    use HasFactory;

    protected $table = 'ip_pools';

    protected $fillable = [
        'name',
        'ranges',
        'router',
    ];
}

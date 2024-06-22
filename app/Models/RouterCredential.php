<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RouterCredential extends Model
{
    use HasFactory;
    protected $table = 'routers';
    protected $fillable = [
        'ip_address',
        'username',
        'password',
    ];
}

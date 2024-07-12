<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RouterCredential extends Model
{
    use HasFactory;
    public $timestamps = false;
    protected $table = 'nas';
    
    protected $fillable = [
        'nasname',
        'shortname',
        'type',
        'radius_server_ip',
        'secret',
        'username',
        'password',
        'description'
    ];
}

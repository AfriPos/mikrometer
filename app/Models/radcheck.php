<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class radcheck extends Model
{
    use HasFactory;
    protected $table = 'radcheck';
    public $timestamps = false;
    protected $fillable = [
        'username',
        'attribute', 
        'op', 
        'value',
    ];
}
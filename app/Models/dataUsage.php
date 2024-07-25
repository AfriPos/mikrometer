<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class dataUsage extends Model
{
    use HasFactory;
    protected $table = 'data_usage_by_period';
    protected $fillable = [
        'username',
        'period_start',
        'period_end',
        'acctinputoctets',
        'acctoutputoctets',
    ];
}

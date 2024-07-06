<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class radgroupreply extends Model
{
    use HasFactory;

    protected $table = 'radgroupreply';
    public $timestamps = false;
    protected $fillable = [
        'groupname',
        'attribute',
        'op',
        'value',
    ];
}

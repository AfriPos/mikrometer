<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class IPAddressesModel extends Model
{
    use HasFactory;
    protected $table = 'ip_addresses';
    protected $fillable = [
        'ip_address',
        'is_used',
        'customer_id',
        'allocated_at',
        'usable',
        'pool_id',
    ];

    public function pool()
    {
        return $this->belongsTo(PoolModel::class);
    }

}


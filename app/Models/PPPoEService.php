<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PPPoEService extends Model
{
    use HasFactory;

    protected $table = 'pppoe_services';
    public $timestamps = false; 

    protected $fillable = [
        'interface',
        'service_name',
        'max_mtu',
        'max_mru',
        'profile_id',
        'disabled',
    ];
    
    public function profile()
    {
        return $this->belongsTo(PPPoEProfile::class);
    }
}

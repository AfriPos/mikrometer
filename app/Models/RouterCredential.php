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
        'description',
        'ip_pool',
        'configured',
    ];

    /**
     * Mark the router as configured.
     *
     * @return void
     */
    public function markAsConfigured()
    {
        $this->update(['configured' => true]); // Set 'configured' to true in the database
    }
}

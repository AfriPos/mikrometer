<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class radacct extends Model
{
    use HasFactory;
    protected $table = 'radacct';
    public $timestamps = false;
    protected $fillable = [
        'radacctid',
        'acctsessionid',
        'acctuniqueid',
        'username',
        'realm',
        'nasipaddress',
        'nasportid',
        'nasporttype',
        'acctstarttime',
        'acctupdatetime',
        'acctstoptime',
        'acctinterval',
        'acctsessiontime',
        'acctauthentic',
        'connectinfo_start',
        'connectinfo_stop',
        'acctinputoctets',
        'acctoutputoctets',
        'calledstationid',
        'callingstationid',
        'acctterminatecause',
        'servicetype',
        'framedprotocol',
        'framedipaddress',
        'framedipv6address',
        'framedipv6prefix',
        'framedinterfaceid',
        'delegatedipv6prefix'
    ];
}

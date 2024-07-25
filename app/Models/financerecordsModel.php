<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class financerecordsModel extends Model
{
    use HasFactory;
    protected $table = 'financial_records';
    protected $fillable = [
        'type',
        'recordable',
        'amount',
        'payment_method',
        'transaction_id',
        'comment',
        'reason',
        'name',
        'description',
        'customer_id',
    ];

    public function recordable()
    {
        return $this->morphTo();
    }
}

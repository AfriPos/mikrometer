<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class paymentModel extends Model
{
    use HasFactory;
    protected $table = 'payments';
    protected $fillable = [
        'customer_id',
        'amount',
        'payment_method',
        'transaction_id',
        'comment',
    ];
    public function customer()
    {
        return $this->belongsTo(CustomerModel::class);
    }
    
    public function records()
    {
        return $this->morphMany('App\Models\financerecordsModel', 'recordable');
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InvoiceModel extends Model
{
    use HasFactory;
    protected $table = 'invoices';
    protected $fillable = [
        'customer_id',
        'amount',
        'due_date',
        'status',
        'type',
    ];
    public function customer()
    {
        return $this->belongsTo(CustomerModel::class);
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FutureItemModel extends Model
{
    use HasFactory;

    public function records()
    {
        return $this->morphMany('App\Models\financerecordsModel', 'recordable');
    }
}

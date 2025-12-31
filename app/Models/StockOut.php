<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StockOut extends Model
{
    protected $fillable = [
        'item_id',
        'qty',
        'date',
        'source',
        'reference_no',
        'status',
        'note',
        'created_by',
        'approved_by',
    ];

    public function item()
    {
        return $this->belongsTo(Item::class);
    }
}

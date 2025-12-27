<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Stock extends Model
{
    protected $fillable = [
        'item_id',
        'qty',
    ];

    public function item()
    {
        return $this->belongsTo(Item::class);
    }
}

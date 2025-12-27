<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Item extends Model
{
    use SoftDeletes;

    protected $dates = ['deleted_at'];
    protected $fillable = [
        'unit_id',
        'category_id',
        'name',
        'code',
        'stock_minimum',
        'location',
        'is_active',
    ];

    public function unit()
    {
        return $this->belongsTo(Unit::class);
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function stockIn()
    {
        return $this->hasMany(StockIn::class);
    }

    public function stock()
    {
        return $this->hasMany(Stock::class);
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Employee extends Model
{
    protected $fillable = [
        'name',
        'position_id',
        'join_date',
        'status',
    ];

    public function position()
    {
        return $this->belongsTo(Position::class);
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UnitScale extends Model
{
    protected $fillable = [
        'label',
        'multiplier',
        'devider'
    ];

    protected $hidden = [
        'created_at',
        'updated_at',
        'unit_id'
    ];
}

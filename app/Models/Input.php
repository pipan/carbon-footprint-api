<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Input extends Model
{
    protected $fillable = [
        'name',
        'unit_id',
        'default_value'
    ];

    protected $hidden = [
        'model_id',
        'unit_id',
        'created_at',
        'updated_at'
    ];

    public function unit()
    {
        return $this->belongsTo(Unit::class);
    }
}

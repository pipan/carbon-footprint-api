<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Component extends Model
{
    protected $fillable = [
        'name',
        'output_unit_id',
        'model_id',
        'schema'
    ];

    protected $hidden = [
        'created_at',
        'updated_at'
    ];

    protected $casts = [
        'schema' => 'array',
    ];
}

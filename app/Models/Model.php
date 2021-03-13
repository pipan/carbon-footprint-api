<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model as EloquentModel;

class Model extends EloquentModel
{
    protected $fillable = [
        'name',
        'output_unit_id',
        'description',
        'components'
    ];

    protected $hidden = [
        'created_at',
        'updated_at'
    ];

    protected $casts = [
        'components' => 'array',
    ];

    public function inputs()
    {
        return $this->hasMany(Input::class);
    }
}

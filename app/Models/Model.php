<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model as EloquentModel;

class Model extends EloquentModel
{
    protected $fillable = [
        'name',
        'output_unit_id',
        'description'
    ];

    protected $hidden = [
        'created_at',
        'updated_at'
    ];

    public function inputs()
    {
        return $this->hasMany(Input::class);
    }

    public function components()
    {
        return $this->hasMany(Component::class);
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Unit extends Model
{
    protected $fillable = [ 'name' ];

    protected $hidden = [
        'created_at',
        'updated_at'
    ];

    public function scales()
    {
        return $this->hasMany(UnitScale::class);
    }
}

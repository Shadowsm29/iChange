<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Supercircle extends Model
{
    protected $fillable = [
        "name"
    ];

    public function circles()
    {
        return $this->hasMany(Circle::class);
    }
}

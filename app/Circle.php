<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Circle extends Model
{
    protected $fillable = [
        "name", "supercircle_id"
    ];

    public function supercircle()
    {
        return $this->belongsTo(Supercircle::class);
    }
}

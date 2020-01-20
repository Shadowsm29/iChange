<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ChangeType extends Model
{
    protected $fillable = [
        "name"
    ];

    public static $COSMOS = 1;
    public static $IT = 2;
    public static $BUSINESS = 3;
    public static $ORGANIZATIONAL = 4;
    public static $JUST_DO_IT = 5;
    public static $RPA = 6;
    public static $LSS = 7;
}

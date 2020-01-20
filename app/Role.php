<?php

namespace App;

class Role extends Model
{
    public static $SUPER_ADMIM = "superadmin";
    public static $USER = "user";
    public static $CHG_BOARD = "changeboard";
    public static $LSS = "lss";
    public static $ADVANCED_USER = "advanceduser";
    public static $MT = "mt";
    public static $RPA = "rpa";
    public static $IT = "it";
    public static $COSMOS = "cosmos";
    public static $IAM = "iam";
    
    public function users()
    {
        return $this->belongsToMany(User::class);
    }
}

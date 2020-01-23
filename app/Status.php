<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Status extends Model
{
    public static $INIT_LINE_MAN_APPR = 1;
    public static $INIT_MT_APPR = 2;
    public static $INIT_CENT_RES_APPR = 3;
    public static $INIT_CHG_BOARD_APPR = 4;
    public static $APPR_SME_ASSGN = 5;
    public static $WIP_JUST_DO_IT = 6;
    public static $WIP_RPA = 7;
    public static $WIP_COSMOS = 8;
    public static $WIP_LSS = 9;
    public static $CORR_NEEDED = 10;
    public static $CANCELLED = 11;
    public static $IMPLEMENTED = 12;
    public static $DECLINED = 13;
    public static $FIN_MT_APPR = 14;
    public static $WIP_IT = 15;
    public static $WIP_ORGANIZATIONAL = 16;
    public static $WIP_BUSINESS = 17;
}

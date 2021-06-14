<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Layout extends Model
{
    use HasFactory;
    
    public static $TITLE;
    public static $CSS_CLASS;
    public static function myTitle(){
        return self::$TITLE ? self::$TITLE : config('app.name');
    }
    public static function myCssClass(){
        return self::$CSS_CLASS ? self::$CSS_CLASS : 'dashboard';
    }
}

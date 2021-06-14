<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Session;

class Usuario extends Model
{
    use HasFactory;
    /*nao possui as colunas created_at e updated_at*/
    public $timestamps = false;
    
    public static function verificaLogin(){
        return is_array(Session::get('logado'));
    }
    public static function getSessionVar($key){
        $logado = Session::get('logado');
        return $logado[$key];
    }
}

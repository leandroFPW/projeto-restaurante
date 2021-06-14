<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pedidostatus extends Model
{
    use HasFactory;
    /*nao possui as colunas created_at e updated_at*/
    public $timestamps = false;
    public $table = 'pedidostatus';
    const status_Novo = 1;
    const status_Pago = 2;
    const status_Empreparo = 3;
    const status_Pronto = 4;
    const status_Entregue = 5;
}

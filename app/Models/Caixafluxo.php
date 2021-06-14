<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Caixafluxo extends Model
{
    use HasFactory;
    /*nao possui as colunas created_at e updated_at*/
    public $timestamps = false;
}

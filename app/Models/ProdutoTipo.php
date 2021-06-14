<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Produto;

class ProdutoTipo extends Model
{
    use HasFactory;
    /*nao possui as colunas created_at e updated_at*/
    public $timestamps = false;
    public $table = 'produtotipos';
    
    public function produtos(){
        return $this->hasMany(Produto::class,'produtotipo_id');
    }
}

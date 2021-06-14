<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\ProdutoTipo;
use App\Models\Usuario;

class Produto extends Model
{
    use HasFactory;
    /*nao possui as colunas created_at e updated_at*/
    public $timestamps = false;
    
    static function listagemTipos(){
        $listagem = [];
        foreach(ProdutoTipo::select('*')->orderBy('nome')->get() as $tipo){
            //produtos ativos da filial
            $produtos = self::select('id','valor','nome')->where('ativo',1)
                    ->where('filial_id',Usuario::getSessionVar('filiacao'))
                    ->where('produtotipo_id',$tipo->id)->orderBy('nome')->get();
            $listagem[] = ['id'=>$tipo->id,'label'=>$tipo->nome,'icone'=>$tipo->icone,'produtos'=>$produtos];
        }
        return $listagem;
    }
}

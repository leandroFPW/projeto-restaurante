<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

class Controller extends BaseController {
    
    /**
     * 'input_name' (somente chave é igualdade)
     * 'input_name' => 'tipo_where'
     * tipos: '=','<>','>','>=','<','<=','like','%like','like%'
     * @var array 
     */
    protected $_filters = array();

    use AuthorizesRequests,DispatchesJobs,ValidatesRequests;
    
    public function callAction($method, $parameters)
    {
        $ret = $this->afterCallAction($method);
        if(is_null($ret)){
            return parent::callAction($method, $parameters);
        }else{
            return $ret;
        }
    }
    /**
     * chamar funcao e sempre ter return
     * @param string $method
     * @return mixed
     */
    public function afterCallAction($method){return null;}
    
    protected function _filtrar($query,$req_filtro=array()){
        foreach($this->_filters as $name => $tipo){
            if(is_int($name) && isset($req_filtro[$tipo]) && strlen($req_filtro[$tipo])){
                //default '=' sem chave=>valor
                $query = $query->where($tipo,trim($req_filtro[$tipo]));
            }elseif(isset($req_filtro[$name]) && trim($req_filtro[$name])){
                $valor = trim($req_filtro[$name]);
                //seguindo chave=>valor
                if($tipo === 'like'){
                    $query = $query->where($name,'like',"%$valor%");
                }elseif($tipo === 'like%'){
                    $query = $query->where($name,'like',"$valor%");
                }elseif($tipo === '%like'){
                    $query = $query->where($name,'like',"%$valor");
                }else{
                    $query = $query->where($name,$tipo,$valor);
                }
            }
        }
        return $query;
    }
}

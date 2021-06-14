<?php

namespace App\Http\Controllers;

use App\Models\Usuario;
use App\Models\Cliente;
use Illuminate\Http\Request;

class ClientesController extends Controller
{
    protected $_filters = [
        'nome'=>'like',
        'cpf'=>'like'
    ];
    /* listagem (read) se vier id via post é exclusao (delete) */

    public function index(Request $request) {
        $id = $request->post('id');
        if($id >0){
            try {
                Cliente::destroy($id);
                session()->flash('success', "Registro excluído.");
            } catch (Exception $ex) {
                session()->flash('error', 'Não foi possível excluir o registro.');
            }
        }
        $query = Cliente::select('*');
        //filtros
        $query = $this->_filtrar($query,$request->filtro);
        //ordenacao e paginacao
        $query = $query->orderBy('nome')->paginate(16);
        return view('clientes.index', ['resultado' => $query, 'itens' => $query->items(),'filtro'=>$request->filtro]);
    }

    /* form novo registro não vem id, se vier é edição
     * (create/update) */

    public function form(Request $request) {
        $post = $request->all();
        try {
            if ($post['id'] > 0) {
                //update
                $cliente = Cliente::find($post['id']);
                if($cliente){
                    $cliente->nome = $post['nome'];
                    $cliente->cpf = $post['cpf'];
                    $cliente->save();
                    session()->flash('success', "Cliente alterado com sucesso.");
                    return redirect('clientes');
                }
            } else {
                //create
                $cliente = new Cliente();
                $cliente->nome = $post['nome'];
                $cliente->cpf = $post['cpf'];
                $cliente->data_cadastro = now();
                $cliente->save();
                session()->flash('success', "Cliente cadastrado com sucesso.");
                return redirect('clientes');
            }
        } catch (Exception $ex) {
            session()->flash('error', 'Não foi possível salvar o registro.');
        }
    }
    //via GET é busca, via POST é cadastro rápido
    public function atendimento(Request $request){
        $resultado = array();
        if ($request->isMethod('post')) {
            //chega os dados, cadastra e retorna o registro
            $nome = $request->post('nome');
            $cpf = $request->post('cpf');
            if($nome && $cpf){
                $cliente = new Cliente();
                $cliente->nome = $nome;
                $cliente->cpf = $cpf;
                $cliente->data_cadastro = now();
                $cliente->save();
                $resultado = $cliente;
            }
        }else{
            //busca e retorna array de objetos
            $termo = trim($request->get('busca'));
            if(strlen($termo)>0){
                $resultado = Cliente::select('*')->orderBy('nome')
                        ->where('nome','like',"%$termo%")
                        ->orWhere('cpf','like',"%$termo%")
                        ->limit(10)->get();
            }
        }
        return json_encode($resultado);
    }

    //----------------------------
    public function afterCallAction($method) {
        //bloqueia todos os usuarios que nao foram tipo 1 e 2
        $tipo = Usuario::getSessionVar('tipo');
        if (!in_array($tipo, [1, 2,4])) {
            return redirect('/acesso-negado');
        }
        return null;
    }
}

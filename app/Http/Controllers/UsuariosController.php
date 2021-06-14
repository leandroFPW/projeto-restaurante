<?php

namespace App\Http\Controllers;

use App\Models\Usuario;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

//CRUD
class UsuariosController extends Controller {
    
    protected $_filters = [
        'filiacao',
        #'usuarios.nome'=>'like',
        'login'=>'like',
        'usuariotipos_id'
    ];
    /* listagem (read) se vier id via post é exclusao (delete) */

    public function index(Request $request) {
        $id = $request->post('id');
        if($id >0){
            try {
                Usuario::destroy($id);
                session()->flash('success', "Registro excluído.");
            } catch (Exception $ex) {
                session()->flash('error', 'Não foi possível excluir o registro.');
            }
        }
        $query = DB::table('usuarios')
                ->join('usuariotipos', 'usuariotipos.id', '=', 'usuarios.usuariotipos_id')
                ->join('filiais', 'filiais.id', '=', 'usuarios.filiacao')
                ->select('usuarios.*', 'usuariotipos.nome AS tipo', 'filiais.nome AS filial')
                ->where('usuariotipos_id', '>', 1); /*nao exibe os admin*/
        //filtros
        $query = $this->_filtrar($query,$request->filtro);
        //ordenacao e paginacao
        $query = $query->orderByDesc('id')->paginate(10);
        return view('usuarios.index', ['resultado' => $query, 'itens' => $query->items(),'filtro'=>$request->filtro]);
    }

    /* form novo registro não vem id, se vier é edição
     * (create/update) */

    public function form(Request $request) {
        $post = $request->all();
        try {
            if ($post['id'] > 0) {
                //update
                $usuario = Usuario::find($post['id']);
                if($usuario){
                    $usuario->nome = $post['nome'];
                    $usuario->login = $post['login'];
                    $usuario->email = $post['email'];
                    $usuario->senha = $post['senha'] ? md5($post['senha']) : $usuario->senha;
                    $usuario->filiacao = $post['filiacao'];
                    $usuario->usuariotipos_id = $post['usuariotipos_id'];
                    $usuario->save();
                    session()->flash('success', "Login '{$usuario->login}' alterado com sucesso.");
                    return redirect('usuarios');
                }
            } else {
                //create
                $usuario = new Usuario();
                $usuario->nome = $post['nome'];
                $usuario->login = $post['login'];
                $usuario->email = $post['email'];
                $usuario->senha = $post['senha'] ? md5($post['senha']) : md5('123456789');
                $usuario->filiacao = $post['filiacao'];
                $usuario->usuariotipos_id = $post['usuariotipos_id'];
                $usuario->data_cadastro = now();
                $usuario->save();
                session()->flash('success', "Login '{$usuario->login}' cadastrado com sucesso.");
                return redirect('usuarios');
            }
        } catch (Exception $ex) {
            session()->flash('error', 'Não foi possível salvar o registro.');
        }
    }

    /* delete via post */

    public function delete(Request $request) {
        //
    }

    //----------------------------
    public function afterCallAction($method) {
        //bloqueia todos os usuarios que nao foram tipo 1 e 2
        $tipo = Usuario::getSessionVar('tipo');
        if (!in_array($tipo, [1, 2])) {
            return redirect('/acesso-negado');
        }
        return null;
    }

}

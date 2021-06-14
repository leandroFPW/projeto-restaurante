<?php

namespace App\Http\Controllers;

use App\Models\Usuario;
use App\Models\ProdutoTipo;
use Illuminate\Http\Request;

class ProdutoTiposController extends Controller
{
    /* listagem (read) se vier id via post é exclusao (delete) */

    public function index(Request $request) {
        $id = $request->post('id');
        if($id >0){
            try {
                ProdutoTipo::destroy($id);
                session()->flash('success', "Registro excluído.");
            } catch (Exception $ex) {
                session()->flash('error', 'Não foi possível excluir o registro.');
            }
        }
        $query = ProdutoTipo::select('*')->orderBy('nome');
        return view('produtotipos.index', ['itens' => $query->get()]);
    }

    /* form novo registro não vem id, se vier é edição
     * (create/update) */

    public function form(Request $request) {
        $post = $request->all();
        try {
            if ($post['id'] > 0) {
                //update
                $registro = ProdutoTipo::find($post['id']);
                if($registro){
                    $registro->nome = $post['nome'];
                    $registro->icone = $post['icone'];
                    $registro->save();
                    session()->flash('success', "Registro alterado com sucesso.");
                    return redirect('prod-tipos');
                }
            } else {
                //create
                $registro = new ProdutoTipo();
                $registro->nome = $post['nome'];
                $registro->icone = $post['icone'];
                $registro->save();
                session()->flash('success', "Registro cadastrado com sucesso.");
                return redirect('prod-tipos');
            }
        } catch (Exception $ex) {
            session()->flash('error', 'Não foi possível salvar o registro.');
        }
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

<?php

namespace App\Http\Controllers;

use App\Models\Usuario;
use App\Models\Produto;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ProdutosController extends Controller
{
    protected $_filters = [
        'produtos.nome'=>'like',
        'ativo',
        'produtotipo_id'
    ];
    /* listagem (read) se vier id via post é exclusao (delete) */

    public function index(Request $request) {
        $id = $request->post('id');
        if($id >0){
            try {
                Produto::destroy($id);
                session()->flash('success', "Registro excluído.");
            } catch (Exception $ex) {
                session()->flash('error', 'Não foi possível excluir o registro.');
            }
        }
        $query = DB::table('produtos')
                ->join('produtotipos', 'produtotipos.id', '=', 'produtos.produtotipo_id')
                ->join('filiais', 'filiais.id', '=', 'produtos.filial_id')
                ->select('produtos.*', 'produtotipos.nome AS categoria', 'filiais.nome AS filial');
        //filtros
        $query = $this->_filtrar($query,$request->filtro);
        //ordenacao e paginacao
        $query = $query->orderByDesc('ativo')
                ->orderBy('filial_id')
                ->orderBy('nome')
                ->paginate(16);
        return view('produtos.index', ['resultado' => $query, 'itens' => $query->items(),'filtro'=>$request->filtro]);
    }

    /* form novo registro não vem id, se vier é edição
     * (create/update) */

    public function form(Request $request) {
        $post = $request->all();
        try {
            if ($post['id'] > 0) {
                //update
                $produto = Produto::find($post['id']);
                if($produto){
                    //imagem
                    $img = $produto->imagem;
                    if($request->hasFile('imagem')){
                        $n_img = 'p-'.uniqid().'.'.$request->imagem->extension();
                        $upload = $request->imagem->move(public_path('uploads'), $n_img);
                        if($upload){
                            $img = $n_img;
                        }
                    }
                    $produto->nome = $post['nome'];
                    $produto->valor = \Helper::moedaFloat($post['valor']);
                    $produto->produtotipo_id = $post['produtotipo_id'];
                    $produto->ativo = $post['ativo'];
                    $produto->filial_id = $post['filial_id'];
                    $produto->descricao = $post['descricao'];
                    $produto->imagem = $img;
                    $produto->save();
                    session()->flash('success', "Registro alterado com sucesso.");
                    return redirect('produtos');
                }
            } else {
                //imagem
                $img = '';
                if($request->hasFile('imagem')){
                    $n_img = 'p-'.uniqid().'.'.$request->imagem->extension();
                    $upload = $request->imagem->move(public_path('uploads'), $n_img);//$request->imagem->storeAs('produtos', $n_img);
                    if($upload){
                        $img = $n_img;
                    }
                }
                //create
                $produto = new Produto();
                $produto->nome = $post['nome'];
                $produto->valor = \Helper::moedaFloat($post['valor']);
                $produto->produtotipo_id = $post['produtotipo_id'];
                $produto->ativo = $post['ativo'];
                $produto->filial_id = $post['filial_id'];
                $produto->descricao = $post['descricao'];
                $produto->imagem = $img;
                $produto->save();
                session()->flash('success', "Registro cadastrado com sucesso.");
                return redirect('produtos');
            }
        } catch (Exception $ex) {
            session()->flash('error', 'Não foi possível salvar o registro.');
        }
    }

    /* alterar entre ativo e inativo */

    public function status(Request $request) {
        $id = $request->get('id');
        $ativo = $request->get('ativo');
        if($id > 0){
            try {
                $produto = Produto::find($id);
                if($produto){
                    $produto->ativo = $ativo;
                    $produto->save();
                }
            } catch (Exception $ex) {}
        }
        return redirect('produtos');
    }

    //----------------------------
    public function afterCallAction($method) {
        //bloqueia todos os produtos que nao foram tipo 1 e 2
        $tipo = Usuario::getSessionVar('tipo');
        if (!in_array($tipo, [1, 2])) {
            return redirect('/acesso-negado');
        }
        return null;
    }
}

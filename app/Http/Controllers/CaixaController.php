<?php

namespace App\Http\Controllers;

use App\Models\Usuario;
use App\Models\ProdutoTipo;
use App\Models\Caixafluxo;
use App\Models\Pedido;
use App\Models\Pedidostatus;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CaixaController extends Controller
{
    /* listagem (read) se vier id via post é exclusao (delete) */

    public function index(Request $request) {
        $id = $request->post('id');
        if($id >0){
            try {
                $pedido = Pedido::find($id);
                if($pedido->fiado != '1'){
                    $caixa = new Caixafluxo();
                    $caixa->valor = number_format($pedido->valor_final, 2, '.','');
                    $caixa->natureza = "Pedido #".str_pad($pedido->id, 6, "0", STR_PAD_LEFT);
                    $caixa->pedido_id = $pedido->id;
                    $caixa->data_cadastro = now()->toDateString();
                    $caixa->save();
                    return redirect('/caixa');
                }
                session()->flash('success', "Registro excluído.");
            } catch (Exception $ex) {
                session()->flash('error', 'Não foi possível excluir o registro.');
            }
        }
        $query = Caixafluxo::select('*')
                ->where('data_cadastro',now()->toDateString())
                ->orderByDesc('data_cadastro')->get();
        $pedidos_baixa = [];
        foreach($query as $row){
            //pedidos ja lançados em caixa
            if($row->pedido_id > 0){
                $pedidos_baixa[] = $row->pedido_id;
            }
        }
        $pedidos = Pedido::select(['id','mesa','valor_final'])
                ->where('fiado',0)
                ->where(DB::raw('DATE(data_pedido)'),now()->toDateString())
                ->where('status_id',Pedidostatus::status_Entregue);
        if(count($pedidos_baixa)){
            $pedidos = $pedidos->whereNotIn('id',$pedidos_baixa);
        }
        return view('caixa.index', ['itens' => $query,'pedidos'=>$pedidos->get()]);
    }

    /* form novo registro não vem id, se vier é edição
     * (create/update) */

    public function form(Request $request) {
        $post = $request->all();
        try {
            $fluxo = new Caixafluxo();
            if($post['entrada_valor']){
                $fluxo->valor = \Helper::moedaFloat($post['entrada_valor']);
                $fluxo->natureza = $post['entrada_natureza'];
                $fluxo->data_cadastro = now();
                $fluxo->save();
                session()->flash('success', "Entrada de caixa registrada.");
            }
            if($post['saida_valor']){
                $fluxo->valor = \Helper::moedaFloat($post['saida_valor']) * -1;
                $fluxo->natureza = $post['saida_natureza'];
                $fluxo->data_cadastro = now()->toDateString();
                $fluxo->save();
                session()->flash('success', "Saída de caixa registrada.");
            }
            return redirect('/caixa');
        } catch (Exception $ex) {
            session()->flash('error', 'Não foi possível salvar o registro.');
        }
    }
    
    public function relatorio(Request $request){
        $array_filtro = (array)$request->filtro;
        $query = Caixafluxo::select('*')->orderByDesc('data_cadastro');
        //filtros
        if(!isset($array_filtro['data_ini']) || @strlen($array_filtro['data_ini']) == 0){
            //nao pode deixar sem data inicial
            $array_filtro['data_ini'] = now()->toDateString();
        }
        //datas
        if(isset($array_filtro['data_ini']) && @strlen($array_filtro['data_ini'])){
            $query = $query->where(DB::raw('DATE(data_cadastro)'),'>=',$array_filtro['data_ini']);
        }
        if(isset($array_filtro['data_fim']) && @strlen($array_filtro['data_fim'])){
            $query = $query->where(DB::raw('DATE(data_cadastro)'),'<=',$array_filtro['data_fim']);
        }
        return view('caixa.relatorio', ['itens' => $query->get(),'filtro'=>$array_filtro]);
    }

    //----------------------------
    public function afterCallAction($method) {
        //bloqueia todos os usuarios que nao foram tipo 1 e 2
        $tipo = Usuario::getSessionVar('tipo');
        if (!in_array($tipo, [1, 2,5])) {
            return redirect('/acesso-negado');
        }
        return null;
    }
}

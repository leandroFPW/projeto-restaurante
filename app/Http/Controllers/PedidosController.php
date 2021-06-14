<?php

namespace App\Http\Controllers;

use App\Models\Usuario;
use App\Models\Cliente;
use App\Models\Pedido;
use App\Models\Pedidostatus;
use App\Models\Pedidoitens;
use App\Models\Layout;
use App\Models\Produto;
use App\Models\Caixafluxo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PedidosController extends Controller
{
    protected $_filters = [
        'cliente_nome'=>'like',
        'mesa',
        'status_id',
        'cliente'
    ];
    /* listagem (read) se vier id via post é exclusao (delete) */

    public function index(Request $request) {
        $id = $request->post('id');
        if($id >0){
            try {
                $pedido = Pedido::find($id);
                $pedido->status_id = 0;
                $pedido->save();
                session()->flash('success', "Pedido cancelado.");
            } catch (Exception $ex) {
                session()->flash('error', 'Não foi possível cancelar o pedido.');
            }
        }
        $array_filtro = (array)$request->filtro;
        $query = Pedido::select('*');
        //filtros
        if(!isset($array_filtro['data_ini']) || @strlen($array_filtro['data_ini']) == 0){
            //nao pode deixar sem data inicial
            $array_filtro['data_ini'] = now()->toDateString();
        }
        $query = $this->_filtrar($query,$array_filtro);
        //datas
        if(isset($array_filtro['data_ini']) && @strlen($array_filtro['data_ini'])){
            $query = $query->where(DB::raw('DATE(data_pedido)'),'>=',$array_filtro['data_ini']);
        }
        if(isset($array_filtro['data_fim']) && @strlen($array_filtro['data_fim'])){
            $query = $query->where(DB::raw('DATE(data_pedido)'),'<=',$array_filtro['data_fim']);
        }
        //ordenacao e paginacao
        $query = $query->orderByDesc('id')->paginate(16);
        $status = ['0'=>'Cancelado'];
        foreach(Pedidostatus::all() as $row){
            $status[$row->id] = $row->status;
        }
        return view('pedidos.index', ['resultado' => $query, 'itens' => $query->items(),'status'=>$status,'filtro'=>$array_filtro]);
    }

    public function status(Request $request){
        $pedido = Pedido::find($request->id);
        $pedido->status_id = $request->status_id;
        $pedido->save();
        //caixa
        /*if($pedido->fiado != '1' && $pedido->status_id == Pedidostatus::status_Entregue){
            $caixa = new Caixafluxo();
            $caixa->valor = number_format($pedido->valor_final, 2, '.','');
            $caixa->natureza = "Pedido #".str_pad($pedido->id, 6, "0", STR_PAD_LEFT);
            $dt = explode(" ", $pedido->data_pedido);
            $caixa->data_cadastro = $dt[0];
            $caixa->pedido_id = $pedido->id;
            $caixa->save();
        }*/
        return redirect()->back();
    }
    public function itens(Request $request){
        $atendente = Usuario::find($request->atd);
        $itens='';
        $query = Pedidoitens::select(['produto_nome','quantidade'])->where('pedido_id',$request->pid)->get();
        foreach($query as $row){
            $itens .= "<li>{$row->quantidade}x {$row->produto_nome}</li>";
        }
        $resultado = new \stdClass();
        $resultado->atendente = $atendente->nome;
        $resultado->itens = $itens;
        return json_encode($resultado);
    }
    
    ## ATENDIMENTO ##
    public function atendimento(Request $request){
        if ($request->isMethod('post')) {
            //cria pedido e redireciona para /confirmado
            $pedido_obj = json_decode($request->post('pedido'));
            try{
                $cliente = Cliente::find($pedido_obj->cliente);
                $pedido = new Pedido();
                $pedido->mesa = $pedido_obj->mesa;
                $pedido->valor_final = $pedido_obj->total;
                $pedido->fiado = $pedido_obj->fiado;
                $pedido->cliente = $pedido_obj->cliente;
                $pedido->cliente_nome = $cliente ? $cliente->nome : 'Visitante';
                $pedido->status_id = 1;
                $pedido->filial_id = Usuario::getSessionVar('filiacao');
                $pedido->atendente_id = Usuario::getSessionVar('id');
                $pedido->data_pedido = now();
                $pedido->descricao = $pedido_obj->obs;
                $pedido->save();
                //pedido criado, registra-se os itens
                foreach($pedido_obj->produtos as $item){
                    $produto = Produto::find($item->prod);
                    $pedido_item = new Pedidoitens();
                    $pedido_item->produto_id = $produto->id;
                    $pedido_item->produto_valor = $produto->valor;
                    $pedido_item->produto_nome = $produto->nome;
                    $pedido_item->quantidade = $item->qtde;
                    $pedido_item->pedido_id = $pedido->id;
                    $pedido_item->save();
                }
                return redirect('/confirmado?p='.$pedido->id);
            } catch (Exception $ex) {
                session()->flash('error', 'Não foi possível salvar o pedido.');
            }
        }
        Layout::$TITLE = 'Atendimento';
        return view('pedidos.atendimento',[
            'lista_mesas' => \Helper::getConfig("lista_mesas"),
            'pedidos_mesas' => Pedido::emPreparo(),
            'produto_tipos' => Produto::listagemTipos()
                ]);
    }
    public function confirmado(Request $request){
        return view('pedidos.confirmado',['pedido'=>$request->get('p')]);
    }
    
    public function cozinha(Request $request){
        $limite_comandas = (int)\Helper::getConfig("limite_comandas");
        $fila = Pedido::select('id','mesa','status_id','descricao','data_pedido')
                ->where('filial_id',Usuario::getSessionVar('filiacao'))
                ->where('status_id','<',Pedidostatus::status_Entregue)
                ->where('status_id','<>',0) /*status 0 é cancelado*/
                ->limit($limite_comandas)->get();
        return view('pedidos.cozinha',['fila'=>$fila,'n_colunas'=>$limite_comandas]);
    }
    
    public function cozinhaStatus(Request $request){
        $pedido = Pedido::find($request->get('pid'));
        $pedido->status_id = $request->get('s');
        $pedido->save();
        return redirect('/cozinha');
    }
    
    public function mesaEntregue(Request $request){
        $pedido = Pedido::find($request->get('pid'));
        $pedido->status_id = Pedidostatus::status_Entregue;
        $pedido->save();
        return redirect('/atendimento');
    }
    
    public function fiados(Request $request){
        $pedido_id = $request->post('pedido_id');
        $pgto_data = $request->post('pgto_data');
        if($pedido_id >0 && $pgto_data){
            try {
                $pedido = Pedido::find($pedido_id);
                $pedido->status_id = Pedidostatus::status_Pago;
                $pedido->save();
                //lança como entrada
                $caixa = new Caixafluxo();
                $caixa->valor = number_format($pedido->valor_final, 2, '.','');
                $caixa->natureza = "Pedido #".str_pad($pedido->id, 6, "0", STR_PAD_LEFT).' quitado';
                $caixa->pedido_id = $pedido->id;
                $caixa->data_cadastro = $pgto_data;
                $caixa->save();
                session()->flash('success', "Pedido quitado.");
                return redirect()->back();
            } catch (Exception $ex) {
                session()->flash('error', 'Não foi possível quitar o pedido.');
            }
        }
        $query = Pedido::select(['id','valor_final','cliente','cliente_nome','atendente_id','data_pedido'])
                ->where('status_id',Pedidostatus::status_Entregue)
                ->where('fiado','1');
        $query = $this->_filtrar($query,$request->filtro);
        $query = $query->paginate(16);
        return view('pedidos.fiados', ['resultado' => $query, 'itens' => $query->items(),'filtro'=>$request->filtro]);
    }

    //----------------------------
    public function afterCallAction($method) {
        Layout::$CSS_CLASS = 'atendimento';
        //bloqueia todos os usuarios que nao foram tipo 1 e 2
        /*$tipo = Usuario::getSessionVar('tipo');
        if (!in_array($tipo, [1, 2,4])) {
            return redirect('/acesso-negado');
        }*/
        return null;
    }
}

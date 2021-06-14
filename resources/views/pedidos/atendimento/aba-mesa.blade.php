<div class="col-sm-9 conteudo" id="conteudo-mesa" data-mesa="">
    <div class="row">
        <?php use App\Models\Pedidostatus;
        foreach(explode(',', $lista_mesas) as $m){ 
            $pedido = isset($pedidos_mesas[trim($m)]) ? $pedidos_mesas[trim($m)] : false;
            ?>
        <div class="col-xs-6 col-sm-6 col-md-3">
            <div class="box box-mesa <?php echo ($pedido && $pedido->status_id == 4) ? 'em-processo':'';?> a-center" 
                 id="box-mesa-1" <?php if(!$pedido){ ?>onclick="escolheMesa('{{trim($m)}}')" <?php } ?>>
                <div class="box-txt">Mesa</div>
                <span class="badge badge-pill badge-dark">{{trim($m)}}</span>
                <div class="a-center qtde"><?php if($pedido){ 
                    echo ($pedido->status_id == Pedidostatus::status_Pronto) ? 'Pronto!' : 'Preparando pedido';
                }else{
                    echo '-';
                }
                ?></div>@if($pedido)
                <a href="{{route('mesa-entregue')}}?pid={{$pedido->id}}" class="btn btn-success mesa-entregue">Entregue</a>
                @else <a href="#" class="btn mesa-entregue">-</a>
                @endif
            </div>
        </div>
        <?php } ?>
        <div class="clearfix"></div>
    </div>
</div>
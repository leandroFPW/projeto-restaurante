@extends('layout_cozinha')<?php /* toda estrutura html */ use App\Models\Pedidostatus; ?>
@section('content') <?php /* yeld(content) */ ?>
<h2 class="text-center">Pedidos na Cozinha | 
    <a href="{{route('home')}}"><i class="fas fa-tachometer-alt" style="font-size: 24px;"> Dashboard</i></a>
</h2>
@include('_html.msg')
<div class="area-table"><?php $fsize = \Helper::getConfig("tamanho_fonte_cozinha");?>
<table class="table" style="font-size: {{$fsize}}">
    <tbody>
        <tr>
            <?php for($i=0;$i<$n_colunas;$i++){ ?>
            <td class="td-comanda td-comanda-{{$i}}" style="width: <?php echo 1/$n_colunas;?>%;">
                <?php if(isset($fila[$i])){ 
                    $comanda = $fila[$i];
                    ?>
                <div class="bg bg-status-{{$comanda->status_id}}">
                    <p class="a-center"><b>#{{str_pad($comanda->id, 5, "0", STR_PAD_LEFT)}} - Mesa {{$comanda->mesa}}</b></p>
                    <p class="acoes">
                        <a class="btn btn-warning" style="font-size: {{$fsize}}" href="{{route('cozinha-status')}}?pid={{$comanda->id}}&s={{Pedidostatus::status_Empreparo}}">Preparar</a><span 
                            class="sep"></span><a class="btn btn-success" style="font-size: {{$fsize}}" href="{{route('cozinha-status')}}?pid={{$comanda->id}}&s={{Pedidostatus::status_Pronto}}">Concluir</a>
                    </p>
                    <?php foreach($comanda->itens()->get() as $item){ ?>
                    <p><b>{{$item->quantidade}}x</b> {{$item->produto_nome}}</p>
                    <?php } ?>
                    <?php if($comanda->descricao){ ?><p><i>{{$comanda->descricao}}</i></p><?php } ?>
                    <div class="a-right">há <?php echo \Helper::tempoEspera($comanda->data_pedido);?></div>
                </div>
                <?php } ?>
            </td>
            <?php } ?>
        </tr>
    </tbody>
</table></div>
<script type="text/javascript">
function refreshing(){
    document.location.reload(false);
}
//da refresh a cada 5seg
var contadorR = setInterval(refreshing,<?php echo (int)\Helper::getConfig("tempoat_cozinha");?>000);
$('.acoes a').on('click',function(){
    clearInterval(contadorR);
});
</script>
@endsection
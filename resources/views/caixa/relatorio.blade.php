@extends('layout_geral')<?php /* toda estrutura html */ ?>
@section('content') <?php /* yeld(content) */ ?>
@include('_html.msg')
<h2>Relatório - Fluxo de Caixa</h2>
@include('caixa.filtro')
<?php
$total = 0;
if(count($itens)){ 
    foreach($itens as $item){
        $total += $item->valor;
    }
}
?>
<h4>TOTAL CAIXA R$: <b style="color: #<?php echo ($total <= 0) ? 'd62929' : '2996d6';?>;">{{Helper::valorReais($total)}}</b></h4>
<div class="row">
    <div class="col-xs-12 col-sm-5">
        <div class="table-responsive">
            <table id="grid-usuarios" class="table table-bordred table-striped">
                <thead>
                    <th>Valor</th>
                    <th>Data</th>
                    <th>Natureza</th>
                </thead>
                <tbody>
                    <?php 
                    if(count($itens)){ 
                        foreach($itens as $item){ ?>
                    <tr>
                        <td>{{Helper::valorReais($item->valor)}}</td>
                        <td>{{Helper::formatarData($item->data_cadastro)}}</td>
                        <td>{{$item->natureza}}</td>
                    </tr>
                        <?php }
                    }else{ ?>
                    <tr><td colspan="9" class="a-center">Nenhum registro encontrado</td></tr>
                    <?php } ?>
                </tbody>
            </table>
            <div class="clearfix"></div>
        </div>
    </div>
</div>
@include('_html.js-grid')
<script src="{{ asset('js/jquery.maskMoney.js') }}"></script>
<script type="text/javascript">
$('.input-money').maskMoney({thousands:'', decimal:',', allowZero:true, suffix: ''});
</script>
@endsection
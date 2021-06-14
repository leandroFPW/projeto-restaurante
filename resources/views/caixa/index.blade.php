@extends('layout_geral')<?php /* toda estrutura html */ ?>
@section('content') <?php /* yeld(content) */ ?>
@include('_html.msg')
<h2>Caixa</h2>
<div class="row">
    <div class="col-xs-12 col-sm-6">
        <div class="table-responsive">
            <form action="{{route('caixa')}}" method="post" onsubmit="return confirm('Confirmar pedido como entrada:');"> @csrf
            <table id="grid-usuarios" class="table">
                <thead>
                    <th>Pedido</th>
                    <th>Valor</th>
                    <th>Mesa</th>
                    <th style="width: 20px;">Ações</th>
                </thead>
                <tbody>
                    <?php 
                    if(count($pedidos)){ 
                        foreach($pedidos as $item){ ?>
                    <tr id="reg-{{$item->id}}">
                        <td>#{{str_pad($item->id, 6, "0", STR_PAD_LEFT)}}</td>
                        <td>{{Helper::valorReais($item->valor_final)}}</td>
                        <td>{{$item->mesa}}</td>
                        <td>
                            <form action="" method="post" onsubmit="return confirm('Confirmar Entrada?');">
                                <input type="hidden" name="id" value="{{$item->id}}" /> @csrf 
                                <button class="btn btn-success btn-xs" type="submit">
                                    <span data-placement="top" data-toggle="tooltip" title="Lançar" class="glyphicon glyphicon-ok-sign"></span>
                                </button>
                            </form>
                        </td>
                    </tr>
                        <?php }
                    }else{ ?>
                    <tr><td colspan="9" class="a-center">Nenhum registro encontrado</td></tr>
                    <?php } ?>
                </tbody>
            </table>
            <div class="clearfix"></div>
            </form>
        </div>
    </div>
    <div class="col-xs-12 col-sm-6">
        <div class="form-group">
            <label>Inserir Entrada</label>
            <form action="{{route('caixa-form')}}" method="post"> @csrf
                <table id="caixa-entradas" class="table">
                    <tbody>
                        <tr>
                            <td>
                                <input type="text" class="form-control input-money input-entradas" placeholder="Valor R$" name="entrada_valor" autocomplete="off" />
                            </td>
                            <td>
                                <input type="text" class="form-control input-entradas" placeholder="Natureza da Entrada" name="entrada_natureza" autocomplete="off" />
                            </td>
                            <td style="width: 10px;">
                                <button class="btn btn-success" type="submit" onclick="$('.input-saidas').val('');"><i class="fas fa-plus"></i></button>
                            </td>
                        </tr>
                    </tbody>
                </table>
                <hr />
                <label>Inserir Saida</label>
                <table id="caixa-saidas" class="table">
                    <tbody>
                        <tr>
                            <td>
                                <input type="text" class="form-control input-money input-saidas" placeholder="Valor R$" name="saida_valor" autocomplete="off" style="color: red;" />
                            </td>
                            <td>
                                <input type="text" class="form-control input-saidas" placeholder="Natureza da Entrada" name="saida_natureza" autocomplete="off" />
                            </td>
                            <td style="width: 10px;">
                                <button class="btn btn-danger" type="submit" onclick="$('.input-entradas').val('');"><i class="fas fa-minus"></i></button>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </form>
        </div>
    </div>
</div>
<?php
$total = 0;
if(count($itens)){ 
    foreach($itens as $item){
        $total += $item->valor;
    }
}
?>
<h2>Entradas e Saídas - {{Helper::formatarData(now())}}</h2>
<h4>TOTAL CAIXA R$: <b style="color: #<?php echo ($total <= 0) ? 'd62929' : '2996d6';?>;">{{Helper::valorReais($total)}}</b></h4>
<div class="row">
    <div class="col-xs-12 col-sm-6">
        <div class="table-responsive">
            <table id="grid-usuarios" class="table table-bordred table-striped">
                <thead>
                    <th>Valor</th>
                    <th>Natureza</th>
                </thead>
                <tbody>
                    <?php 
                    if(count($itens)){ 
                        foreach($itens as $item){ ?>
                    <tr>
                        <td>{{Helper::valorReais($item->valor)}}</td>
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
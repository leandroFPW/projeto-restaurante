@extends('layout_geral')<?php /* toda estrutura html */ ?>
@section('content') <?php /* yeld(content) */ ?>
<h2>Pedidos Fiados</h2>
@include('_html.msg')
@include('pedidos.filtro-fiados')
<div class="table-responsive">
    <table id="grid-pedidos" class="table table-bordred table-striped">
        <thead>
            <th style="width: 20px;">Número</th>
            <th>Cliente</th>
            <th>Data</th>
            <th class="nowrap">Valor</th>
            <th style="width: 200px;" class="nowrap">Pagamento na data</th>
        </thead>
        <tbody>
            <?php 
            if(count($itens)){ 
                foreach($itens as $item){ ?>
            <tr id="reg-{{$item->id}}">
                <td class="td-id">#{{str_pad($item->id, 6, "0", STR_PAD_LEFT)}}</td>
                <td class="td-cliente nowrap">{{$item->cliente_nome}}</td>
                <td class="nowrap">{{Helper::formatarData($item->data_pedido,true)}}</td>
                <td class="td-total nowrap">{{Helper::valorReais($item->valor_final)}}</td>
                <td class="nowrap">
                    <form action="" method="post" onsubmit="return confirm('Confirmar pagamento?');">
                        <input type="hidden" name="pedido_id" value="{{$item->id}}" /> @csrf 
                        <input type="date" name="pgto_data" required class="form-control" style="width: 170px;display: inline-block;" />
                        <button class="btn btn-success" type="submit">
                            <span class="glyphicon glyphicon-ok"></span> Quitar
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
    <div class="a-right"> <?php /*pagination incluindo parametros get preenchidos com layout de bootstrap*/ ?>
        {{ $resultado->appends(request()->input())->links('pagination::bootstrap-4') }}
    </div>
</div>
<!-- modal form -->
<div class="modal fade" id="modal-form" tabindex="-1" role="dialog" aria-labelledby="edit" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">
                    <span class="glyphicon glyphicon-remove" aria-hidden="true"></span>
                </button>
                <h4 class="modal-title custom_align" id="modal-heading"><span>Alterar</span></h4>
            </div>
            <form action="{{route('pedidos-status')}}" method="post"> @csrf
                <div class="modal-body">
                    <div class="row">
                        <div class="col-xs-6" id="edit-descricao">
                            <p id="p-modal-pedido"></p>
                        </div>
                        <div class="col-xs-6 col-md-4">
                            <div class="form-group">
                                <select required id="form-status_id" class="form-control" name="status_id">
                                    <?php Helper::formOptions('pedidostatus',null,'status','id');?>
                                </select>
                            </div>
                        </div>
                        <div class="clearfix"></div>
                    </div>
                </div>
                <div class="modal-footer ">
                    <input type="hidden" id="form-id" name="id" value="" />
                    <button type="submit" class="btn btn-warning btn-lg" style="width: 100%;"><span
                            class="glyphicon glyphicon-ok-sign"></span> Salvar</button>
                </div>
            </form>
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>
<div class="modal fade" id="modal-info" tabindex="-2" role="dialog" aria-labelledby="edit" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">
                    <span class="glyphicon glyphicon-remove" aria-hidden="true"></span>
                </button>
                <h4 class="modal-title custom_align" id="modal-heading"><span>Alterar</span></h4>
            </div>
            <div class="modal-body">
                <div id="modal-body-loading">Carregando informações...</div>
                <div class="row" id="modal-body-detalhes" style="display: none;">
                    <div class="col-xs-6">
                        <ul class="modal-ul" id="pedido-itens-modal"></ul>
                        <i id="pedido-infor-obs"></i>
                    </div>
                    <div class="col-xs-6">
                        <p>Pedido <b id="pedido-infor-id"></b></p>
                        <p>Cliente <b id="pedido-infor-cliente"></b></p>
                        <p>Total <b id="pedido-infor-total"></b></p>
                        <p>Atendente <b id="pedido-infor-atendente"></b></p>
                    </div>
                    <div class="clearfix"></div>
                </div>
            </div>
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>
@include('_html.js-grid')
<!--script src="{{ asset('js/jquery.mask.js') }}"></script-->
<script type="text/javascript">
//$('.mask-date').mask('00/00/0000', {clearIfNotMatch: true});
function modalForm(id,btn){
    $('#modal-heading span').html($(btn).attr('data-title'));
    if(id > 0){
        $('#p-modal-pedido').html("Pedido <b>"+$('#reg-'+id+" .td-id").html()+"</b> de "+$('#reg-'+id+" .td-cliente").html());
        //se tiver id passado, pega o json na <tr> e usa pra preencher
        var str = $('#reg-'+id).attr('data-json');
        var obj = JSON.parse(str);
        $('#form-status_id').val(obj.status_id);
        $('#form-id').val(id);
        $('#obs-img').show();
    }
}
function modalInfo(id,btn){
    $('#modal-heading span').html($(btn).attr('data-title'));
    $('#modal-body-loading').show();
    $('#modal-body-detalhes').hide();
    if(id > 0){
        var str = $('#reg-'+id).attr('data-json');
        var obj = JSON.parse(str);
        var itens = '';
        var atendente = '';
        $.ajax({
            type: 'GET',
            url: "{{route('pedidos-itens')}}?pid="+id+"&atd="+obj.atendente_id,
            dataType: 'JSON',
            success:function(data){
                itens = data.itens;
                atendente = data.atendente;
                $('#modal-body-loading').hide();
                $('#modal-body-detalhes').show();
                $('#pedido-infor-id').html($('#reg-'+id+" .td-id").html());
                $('#pedido-infor-cliente').html($('#reg-'+id+" .td-cliente").html());
                $('#pedido-infor-total').html($('#reg-'+id+" .td-total").html());
                $('#pedido-infor-obs').html(obj.descricao);
                $('#pedido-infor-atendente').html(atendente);
                $('#pedido-itens-modal').html(itens);
            },
            error:function(){
              alert('Erro');
            }
        });
    }
}
</script>
@endsection
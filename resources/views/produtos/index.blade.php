@extends('layout_geral')<?php /* toda estrutura html */ ?>
@section('content') <?php /* yeld(content) */ ?>
<h2>Produtos 
    <button type="button" onclick="modalForm(0,this)" class="btn btn-primary btn-success" data-title="Novas" data-toggle="modal" data-target="#modal-form">Novo</button>
</h2>
@include('_html.msg')
@include('produtos.filtro')
<div class="table-responsive">
    <table id="grid-produtos" class="table table-bordred table-striped">
        <thead>
            <th style="width: 20px;">Ativo?</th>
            <th>Nome</th>
            <th>Categoria</th>
            <th>Valor</th>
            <th>Filial</th>
            <th style="width: 100px;">Ações</th>
        </thead>
        <tbody>
            <?php 
            if(count($itens)){ 
                foreach($itens as $item){ ?>
            <tr id="reg-{{$item->id}}" class="<?php echo ($item->ativo == '1')?'ativo':'inativo';?>" data-json='{"nome":"<?php echo $item->nome;?>","valor":"<?php echo Helper::valorReais($item->valor);?>","produtotipo_id":"<?php echo $item->produtotipo_id;?>","ativo":"<?php echo $item->ativo;?>","imagem":"<?php echo $item->imagem;?>","filial_id":"<?php echo $item->filial_id;?>","descricao":"<?php echo $item->descricao;?>"}'>
                <td><?php echo ($item->ativo == '1')?'Sim':'Não';?></td>
                <td>{{$item->nome}}</td>
                <td>{{$item->categoria}}</td>
                <td>{{Helper::valorReais($item->valor)}}</td>
                <td>{{$item->filial}}</td>
                <td>
                    <form action="" method="post" onsubmit="return confirm('Deseja mesmo excluir?');">
                        <input type="hidden" name="id" value="{{$item->id}}" /> @csrf 
                        <button onclick="modalForm({{$item->id}},this)" data-target="#modal-form" class="btn btn-primary btn-xs" data-title="Editar" data-toggle="modal" type="button">
                            <span data-placement="top" data-toggle="tooltip" title="Editar" class="glyphicon glyphicon-pencil"></span>
                        </button> @if($item->ativo == '1')
                        <button data-placement="top" onclick="window.location = '{{route('produtos-status')}}?id={{$item->id}}&ativo=0';" 
                                data-toggle="tooltip" title="Desativar" class="btn btn-warning btn-xs" type="button">
                            <span class="glyphicon glyphicon-ban-circle" style="padding-right: 2px;"></span>
                        </button>@endif
                        <button data-placement="top" data-toggle="tooltip" title="Excluir"
                                class="btn btn-danger btn-xs" data-title="Excluir" type="submit">
                            <span class="glyphicon glyphicon-trash" style="padding-right: 2px;"></span>
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
                <h4 class="modal-title custom_align" id="modal-heading"><span>Editar</span> Informações</h4>
            </div>
            <form action="{{route('produtos-form')}}" method="post" enctype="multipart/form-data"> @csrf
                <div class="modal-body">
                    <div class="row">
                        <div class="col-xs-6">
                            <div class="form-group">
                                <select required id="form-ativo" class="form-control" name="ativo">
                                    <option style="color: #9a9a9a;" value="">Ativo?*</option>
                                    <option value="0">- Não</option>
                                    <option value="1">- Sim</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-xs-6">
                            <div class="form-group">
                                <select required id="form-filial_id" class="form-control" name="filial_id">
                                    <option style="color: #9a9a9a;" value="">Filial*</option>
                                    <?php Helper::formOptions('filiais');?>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="form-group">
                                <input required id="form-nome" name="nome" class="form-control " type="text" placeholder="Nome*"/>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="form-group">
                                <select required id="form-produtotipo_id" class="form-control" name="produtotipo_id">
                                    <option style="color: #9a9a9a;" value="">Categoria*</option>
                                    <?php Helper::formOptions('produtotipos');?>
                                </select>
                            </div>
                        </div>
                        <div class="col-xs-6">
                            <div class="form-group">
                                <label>Valor*:</label>
                                <input required id="form-valor" name="valor" class="form-control input-money" type="text" placeholder="0,00"/>
                            </div>
                        </div>
                        <div class="col-xs-6">
                            <div class="form-group">
                                <label>Foto: </label>
                                <input type="file" name="imagem" class="form-control"/>
                                <small id="obs-img">Mantenha o campo em branco para não alterar a imagem atual</small>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="form-group">
                                <input id="form-descricao" name="descricao" class="form-control " type="text" placeholder="Descrição"/>
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
@include('_html.js-grid')
<script src="{{ asset('js/jquery.maskMoney.js') }}"></script>
<script type="text/javascript">
$('.input-money').maskMoney({thousands:'', decimal:',', allowZero:true, suffix: ''});
function modalForm(id,btn){
    $('#modal-heading span').html($(btn).attr('data-title'));
    //zera o formulario
    $('#modal-form input[type=text]').val('');
    $('#modal-form select').val('');
    $('#form-id').val('');
    $('#obs-img').hide();
    if(id > 0){
        //se tiver id passado, pega o json na <tr> e usa pra preencher
        var str = $('#reg-'+id).attr('data-json');
        var obj = JSON.parse(str);
        $('#form-nome').val(obj.nome);
        $('#form-ativo').val(obj.ativo);
        $('#form-filial_id').val(obj.filial_id);
        $('#form-produtotipo_id').val(obj.produtotipo_id);
        $('#form-valor').val(obj.valor);
        $('#form-descricao').val(obj.descricao);
        $('#form-id').val(id);
        $('#obs-img').show();
    }
}
</script>
@endsection
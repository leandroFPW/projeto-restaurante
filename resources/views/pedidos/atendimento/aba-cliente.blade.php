<div class="col-sm-9 conteudo" id="conteudo-cliente" style="display: none;">
    <table class="table table-clientes">
        <thead>
            <tr>
                <th class="col-1 label-pesquisar">Pesquisar</th>
                <th class="col-2" colspan="2">
                    <input type="text" style="height: 34px;" class="input-pesquisar" id="pesquisar-cliente" placeholder="Digite CPF ou Nome do cliente" />
                </th>
                <th style="width: 10px;">
                    <button onclick="clientePesq()" class="btn btn-info" type="button"><i class="fas fa-search"></i></button>
                </th>
            </tr>
            <tr>
                <th class="col-2" colspan="2" id="col-nome-cliente" data-cliente-id="">
                    Visitante
                </th>
                <th style="width: 10px;">
                    <button onclick="clienteNovo()" type="button" data-toggle="tooltip" title="Cadastrar" data-title="Cadastrar" class="btn btn-success btn-height">
                        <i class="fas fa-user-plus"></i>
                    </button>
                </th>
                <th style="width: 10px;">
                    <button onclick="clienteEx()" type="button" data-toggle="tooltip" title="Remover" data-title="Remover" class="btn btn-danger btn-height">
                        <i class="fas fa-times-circle"></i>
                    </button>
                </th>
            </tr>
        </thead>
        <tbody style="display:none;"></tbody>
    </table>
    <div id="atendimento-cadastro-cliente" style="display: none;">
        <div class="overlay"></div> @csrf
        <h3>Cadastro Rápido</h3>
        <table class="table">
            <tr>
                <td>
                    <input type="text" id="cad-cli-nome" class="form-control input-text" placeholder="Nome*" />
                </td>
                <td>
                    <input type="text" id="cad-cli-cpf" class="form-control input-text" placeholder="CPF*" />
                </td>
                <th style="width: 50px;">
                    <button onclick="clienteForm()" class="btn btn-success" type="button">Cadastrar</button>
                </th>
            </tr>
        </table>
    </div>
</div>
<script type="text/javascript">
$("[data-toggle=tooltip]").tooltip();
function clienteEx(){
    preencheCliente('Visitante','');
}
function clienteUsar(elem){
    preencheCliente($(elem).html(),$(elem).attr('data-id'));
    $('.table-clientes tbody').hide();
    $('#resumo-fiado').val('0');
    $('#pesquisar-cliente').val('');
}
function preencheCliente(_html,id){
    $('#col-nome-cliente').html(_html).attr('data-cliente-id',id);
    $('#resumo-cliente span').html(_html);
}
function clienteNovo(){
    $('#atendimento-cadastro-cliente').show();
     $('#atendimento-cadastro-cliente .overlay').hide();
}
function clienteForm(){
    var nome = $('#cad-cli-nome').val();
    var cpf = $('#cad-cli-cpf').val();
    if(nome.length > 2 && nome.length > 2){
        $('#atendimento-cadastro-cliente .overlay').show();
        $.ajax({
            type: 'POST',
            url: "{{route('clientes-atendimento')}}",
            dataType: 'JSON',
            data: {
                "nome": nome,"cpf": cpf,_token: "{{ csrf_token() }}"
            },
            success:function(data){
                $('#col-nome-cliente').html(data.nome+' - '+data.cpf).attr('data-cliente-id',data.id);
                $('.table-clientes tbody').hide();
                $('#atendimento-cadastro-cliente .overlay').hide();
                $('#atendimento-cadastro-cliente').hide();
                $('#atendimento-cadastro-cliente input').val('');
            },
            error:function(){
              alert('Erro');
            }
        });
    }else{
        alert('Preencha os campos corretamente');
    }
}
function clientePesq(){
    $('.table-clientes tbody').hide();
    $.ajax({
        type: 'GET',
        url: "{{route('clientes-atendimento')}}",
        dataType: 'JSON',
        data: {
            "busca": $('#pesquisar-cliente').val()
        },
        success:function(data){
            var html = '';
            $.each(data,function(index,val){
                html += '<tr><td colspan="4" onclick="clienteUsar(this)" data-id="'+val.id+'">'+val.nome+' - '+val.cpf+'</td></tr>';
            });
            $('.table-clientes tbody').html(html);
            $('.table-clientes tbody').show();
        },
        error:function(){
          alert('Erro');
        }
    });
}
</script>
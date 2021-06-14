<div class="col-sm-9 conteudo" id="conteudo-confirmacao" style="display: none;">
    <table class="table table-resumo">
        <thead>
            <tr>
                <th colspan="2">Resumo - Mesa <span style="color: #ab0000;" class="mesa-contador">?</span></th>
            </tr>
        </thead>
        <tbody id="resumo-itens"></tbody>
        <tfoot>
            <tr>
                <th>TOTAL</th>
                <th id="resumo-total">00,00</th>
            </tr>
            <tr>
                <th colspan="2" id="resumo-cliente">
                    Cliente <span>Visitante</span>
                    <div>
                        Venda Fiada
                        <select id="resumo-fiado" class="form-control">
                            <option value="0">Não</option>
                            <option value="1">Sim</option>
                        </select>
                    </div>
                </th>
            </tr>
            <tr>
                <td colspan="2">
                    <input type="text" class="form-control" autocomplete="off" placeholder="Observações" id="pedido-obs" value="" />
                    <div class="acao-confirmar">
                        <button type="button" onclick="resumoConfirma()" class="btn btn-success">CONFIRMAR PEDIDO</button>
                        <form id="form-fechar-pedido" action="" method="post">@csrf <input type="hidden" id="pedido-json" name="pedido" value=""/></form>
                    </div>
                </td>
            </tr>
        </tfoot>
    </table>
</div>
<script type="text/javascript">
function resumoConfirma(){
    var cliente = $('#resumo-cliente span').html();
    var clienteId = parseInt($('#col-nome-cliente').attr('data-cliente-id')) || 0;
    var vrfiado = parseInt($('#resumo-fiado').val());
    $('#form-fechar-pedido').val('');
    if(vrfiado === 1 && clienteId === 0){
        alert("Venda fiada para Visitante não permitida!");
    }else{
        if(confirm("Fechar pedido para "+cliente+"?")){
            var pedido = {};//precisa de mesa e carrinho para fechar
            var mesa = $('#conteudo-mesa').attr('data-mesa');
            if(mesa.length > 0){
                if(carrinho_itens.length > 0){
                    pedido["mesa"] = mesa;
                    pedido["produtos"] = carrinho_itens;
                    pedido["cliente"] = clienteId;
                    pedido["fiado"] = vrfiado;
                    pedido["obs"] = $('#pedido-obs').val();
                    pedido["total"] = resumo_total;
                    var json_pedido = JSON.stringify(pedido);
                    //submit
                    $('#pedido-json').val(json_pedido);
                    $('#form-fechar-pedido').submit();
                }else{
                    alert("Escolha um PRODUTO");
                }
            }else{
                alert("Escolha uma MESA");
            }
        }
    }
}
var carrinho_itens = [];
var resumo_total;
function resumirComanda(){
    var resumo_html = '';
    var aux_qtde = 0;
    var aux_id = 0;
    var aux_nome = '';
    var aux_valor = 0.0;
    var total = 0;
    carrinho_itens = [];
    $('.input-qtde').each(function(){
        aux_qtde = parseInt($(this).val());
        aux_id = $(this).attr('data-id');
        if(aux_qtde > 0){
            aux_nome = $('#td-prod-'+aux_id+' .td-item').html();
            aux_valor = aux_qtde * parseFloat($(this).attr('data-preco'));
            total += aux_valor;
            //table
            resumo_html += '<tr><td>'+aux_qtde+'x '+aux_nome+'</td><td>'+floatEmReais(aux_valor)+'</td></tr>';
            //carrinho
            carrinho_itens.push({'prod':aux_id,'qtde':aux_qtde});
        }
    });
    $('#resumo-itens').html(resumo_html);
    $('#resumo-total').html(floatEmReais(total));
    resumo_total = total;
}
</script>
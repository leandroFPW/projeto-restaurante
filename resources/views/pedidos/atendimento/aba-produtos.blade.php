<div class="col-sm-9 conteudo" id="conteudo-produtos" style="display: none;">
    <div class="panel-group" id="accordion-produtos">
        <?php foreach($produto_tipos as $tipo){ ?>
        <!-- categoria {{$tipo['id']}} -->
        <div class="panel panel-default panel-tipo-{{$tipo['id']}}">
            <div class="panel-heading">
                <h4 class="panel-title">
                    <a class="accordion-toggle collapsed" data-toggle="collapse" data-parent="#accordion-produtos" href="#collapse-tipo-{{$tipo['id']}}">
                        @if($tipo['icone'])<i class="fas fa-{{$tipo['icone']}}"></i>@endif {{$tipo['label']}}
                    </a>
                </h4>
            </div>
            <div id="collapse-tipo-{{$tipo['id']}}" class="panel-collapse collapse">
                <div class="panel-body">
                    <table class="simple-table table-itens">
                        <tbody>
                            <?php foreach($tipo['produtos'] as $prod){ ?>
                            <tr id="td-prod-{{$prod->id}}">
                                <td class="td-item">{{$prod->nome}}</td>
                                <td class="td-btn">
                                    <div class="input-group">
                                        <span class="input-group-btn">
                                            <button type="button"
                                                    class="quantity-left-minus btn btn-danger btn-number"
                                                    data-type="minus" data-field="quantity-prod{{$prod->id}}-item">
                                                <span class="glyphicon glyphicon-minus"></span>
                                            </button>
                                        </span>
                                        <input type="text" id="quantity-prod{{$prod->id}}-item" name="quantity" class="form-control input-number input-qtde" data-id="{{$prod->id}}" data-preco="{{$prod->valor}}" value="0"/>
                                        <span class="input-group-btn">
                                            <button type="button" class="quantity-right-plus btn btn-success btn-number" data-type="plus" data-field="quantity-prod{{$prod->id}}-item">
                                                <span class="glyphicon glyphicon-plus"></span>
                                            </button>
                                        </span>
                                    </div>
                                </td>
                            </tr>
                            <?php } ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <?php } ?>
        <!-- -->
    </div>
</div>
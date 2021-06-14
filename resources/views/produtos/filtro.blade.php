<div class="filtros">
    <form action="" method="get">
        <div class="row">
            <div class="col-md-2">
                <div class="form-group">
                    <label>Ativos</label>
                    <select name="filtro[ativo]" class="form-control">
                        <option value="">Tudo</option>
                        <option <?php echo (isset($filtro['ativo']) && $filtro['ativo'] == '1') ? 'selected' : '';?> value="1">Sim</option>
                        <option <?php echo (isset($filtro['ativo']) && $filtro['ativo'] == '0') ? 'selected' : '';?> value="0">Não</option>
                    </select>
                </div>
            </div>
            <div class="col-md-4">
                <div class="form-group">
                    <label>Nome</label>
                    <input type="text" name="filtro[produtos.nome]" value="{{@$filtro['produtos.nome']}}" class="form-control" autocomplete="off" />
                </div>
            </div>
            <div class="col-md-3">
                <div class="form-group">
                    <label>Categoria</label>
                    <select name="filtro[produtotipo_id]" class="form-control">
                        <option value="">Todas</option>
                        <?php Helper::formOptions('produtotipos',@$filtro['produtotipo_id']);?>
                    </select>
                </div>
            </div>
            <div class="col-md-1">
                <div class="form-group">
                    <button class="btn btn-info btn-pesquisar" type="submit"><i class="fas fa-search"></i></button>
                </div>
            </div>
            <div class="cleafix"></div>
        </div>
    </form>
</div>
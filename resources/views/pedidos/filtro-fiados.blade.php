<div class="filtros">
    <form action="" method="get">
        <div class="row">
            <div class="col-md-4">
                <div class="form-group">
                    <label>Cliente</label>
                    <select name="filtro[cliente]" class="form-control">
                        <option value="">Todos</option>
                        <?php Helper::formOptions('clientes',@$filtro['cliente']);?>
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
<div class="filtros">
    <form action="" method="get">
        <div class="row">
            <div class="col-md-3">
                <div class="form-group">
                    <label>Filial</label>
                    <select name="filtro[filiacao]" class="form-control">
                        <option value="">Todas</option>
                        <?php Helper::formOptions('filiais',@$filtro['filiacao']);?>
                    </select>
                </div>
            </div>
            <div class="col-md-4">
                <div class="form-group">
                    <label>Login</label>
                    <input type="text" name="filtro[login]" value="{{@$filtro['login']}}" class="form-control" autocomplete="off" />
                </div>
            </div>
            <div class="col-md-3">
                <div class="form-group">
                    <label>Tipo</label>
                    <select name="filtro[usuariotipos_id]" class="form-control">
                        <option value="">Todas</option>
                        <?php Helper::formOptions('usuariotipos',@$filtro['usuariotipos_id']);?>
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
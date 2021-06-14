<div class="filtros">
    <form action="" method="get">
        <div class="row">
            <div class="col-md-2">
                <div class="form-group">
                    <label>Data Início</label>
                    <input type="date" name="filtro[data_ini]" value="{{@$filtro['data_ini']}}" class="form-control" autocomplete="off" />
                </div>
            </div>
            <div class="col-md-2">
                <div class="form-group">
                    <label>Data Fim</label>
                    <input type="date" name="filtro[data_fim]" value="{{@$filtro['data_fim']}}" class="form-control" autocomplete="off" />
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
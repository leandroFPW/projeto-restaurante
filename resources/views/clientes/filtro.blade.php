<div class="filtros">
    <form action="" method="get">
        <div class="row">
            <div class="col-md-3">
                <div class="form-group">
                    <label>Nome</label><?php /*echo blade ignorando indefined index*/?>
                    <input type="text" name="filtro[nome]" value="{{@$filtro['nome']}}" class="form-control" autocomplete="off" />
                </div>
            </div>
            <div class="col-md-4">
                <div class="form-group">
                    <label>CPF</label>
                    <input type="text" name="filtro[cpf]" value="{{@$filtro['cpf']}}" class="form-control" autocomplete="off" />
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
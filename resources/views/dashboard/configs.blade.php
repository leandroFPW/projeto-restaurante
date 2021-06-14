@extends('layout_geral')<?php /* toda estrutura html */ ?>
@section('content') <?php /* yeld(content) */ ?>
<h1><i class="fas fa-cubes"></i> Configurações</h1>
<div id="form-configs">
    <form action="" method="post"> @csrf
        <div class="row">
            <?php foreach($rows as $row){ ?>
            <div class="col-sm-6">
                <div class="form-group">
                    <label>{{$row->descricao}}</label>
                    <?php if(!$row->select){ ?>
                    <input name="cfg[{{$row->chave}}]" class="form-control " type="text" value="{{$row->valor}}"/>
                    <?php }else{ ?>
                    <select name="cfg[{{$row->chave}}]" class="form-control ">
                        <option value="">terminar</option>
                    </select>
                    <?php } ?>
                </div>
            </div>
            <?php } ?>
            <div class="clearfix"></div>
        </div>
        <button type="submit" class="btn btn-success btn-lg">Salvar</button>
    </form>
</div>
@endsection
<?php
use App\Models\Usuario;
use App\Models\Produto;
use App\Models\Cliente;
use App\Models\Pedido;
?>
@extends('layout_geral')<?php /* toda estrutura html */ ?>
@section('content') <?php /* yeld(content) */ ?>
<h1><i class="fas fa-tachometer-alt"></i> Dashboard</h1>
@if(Helper::loginTemNivel([1,2]))
<div class="resumos">
    <div class="row">
        <div class="col-sm-12 col-md-3">
            <div class="box box-1 a-center">
                <div>
                    <i class="fas fa-user"></i>
                    <div class="box-txt">{{Usuario::count()}} funcionário(s)</div>
                </div>
                <button type="button" class="btn" data-url="{{route('usuarios')}}">Novo</button>
            </div>
        </div>
        <div class="col-sm-12 col-md-3">
            <div class="box box-2 a-center">
                <div>
                    <i class="fas fa-book"></i>
                    <div class="box-txt">{{Produto::count()}} produto(s)</div>
                </div>
                <button type="button" class="btn" data-url="{{route('produtos')}}">Novo</button>
            </div>
        </div>
        <div class="col-sm-12 col-md-3">
            <div class="box box-3 a-center">
                <div>
                    <i class="fas fa-users"></i>
                    <div class="box-txt">{{Cliente::count()}} cliente(s)</div>
                </div>
                <button type="button" data-url="{{route('clientes')}}" class="btn">Novo</button>
            </div>
        </div>
        <div class="col-sm-12 col-md-3">
            <div class="box box-4 a-center">
                <div>
                    <i class="fas fa-shopping-bag"></i>
                    <div class="box-txt">{{Pedido::count()}} pedido(s)</div>
                </div>
                <button type="button" data-url="{{route('pedidos')}}" class="btn">Novo</button>
            </div>
        </div>
    </div>
</div>
<!--div class="list-group">
    <a href="#" class="list-group-item list-group-item-action">
        Novos pedidos
        <span class="badge badge-pill badge-dark">10</span>
    </a>
    <a href="#" class="list-group-item list-group-item-action">
        Pedidos em produção
        <span class="badge badge-pill badge-dark">10</span>
    </a>
    <a href="#" class="list-group-item list-group-item-action">
        Pedidos prontos
        <span class="badge badge-pill badge-dark">10</span>
    </a>
    <a href="#" class="list-group-item list-group-item-action">
        Pedidos suspensos
        <span class="badge badge-pill badge-dark">10</span>
    </a>
</div-->
@else
<p>Seu usuário não possui informações para esta área.</p>
<p>Utilize o menu ao topo para navegar.</p>
@endif
@endsection
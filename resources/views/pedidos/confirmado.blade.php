@extends('layout_geral')<?php /* toda estrutura html */ ?>
@section('content') <?php /* yeld(content) */ ?>
<div class="pedido-confirmado text-center">
    <h2 class="text-center">Pedido #{{str_pad($pedido, 6, "0", STR_PAD_LEFT)}}</h2>
    <p>Pedido confirmado e enviado à cozinha</p>
    <a href="{{route('atendimento')}}" class="btn btn-info">Realizar novo Atendimento</a>
</div>
@endsection
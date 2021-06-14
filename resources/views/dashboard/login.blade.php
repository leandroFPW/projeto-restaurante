@extends('layout_login') <?php /* toda estrutura html */ ?>
@section('content') <?php /* yeld(content) */ ?>
@include('_html.msg')
<div id="form-login" class="form-inicial" >
    <form action="{{url('/login')}}" method="post">
        @csrf
        <label class="form-label">
            <i class="fas fa-user-circle"></i>
            <input class="form-control" type="text" name="usuario" placeholder="Usuário"/>
        </label>
        <label class="form-label">
            <i class="fas fa-lock"></i>
            <input class="form-control" type="password" name="senha" placeholder="Senha"/>
        </label>
        <div class="a-center">
            <button type="submit" class="btn btn-primary">Entrar</button>
            <p><a href="javascript:" onclick="$('.form-inicial').hide();$('#form-senha').show();">Esqueci minha Senha</a></p>
        </div>
    </form>
</div><!-- script vai exibir um ou outro -->
<div id="form-senha" class="form-inicial" style="display: none;">
    <div class="a-center">
        <h2>Esqueci minha Senha</h2>
        <p>Informe seu Usuário e E-mail</p>
    </div>
    <form action="{{url('/senha')}}" method="post">
        @csrf
        <label class="form-label">
            <i class="fas fa-user-circle"></i>
            <input class="form-control" type="text" name="usuario" placeholder="Usuário"/>
        </label>
        <label class="form-label">
            <i class="fas fa-envelope"></i>
            <input class="form-control" type="text" name="email" placeholder="E-mail"/>
        </label>
        <div class="a-center">
            <button type="submit" class="btn btn-primary">Solicitar nova Senha</button>
            <p><a href="javascript:" onclick="$('.form-inicial').hide();$('#form-login').show();">Voltar ao login</a></p>
        </div>
    </form>
</div>
<script type="text/javascript">

</script>
@endsection
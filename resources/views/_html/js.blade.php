<script type="text/javascript">
    $('.btn-number').on('click', function () {
        var id_input = $(this).attr('data-field');
        var qtde = parseInt($('#' + id_input).val());
        if ($(this).attr('data-type') === "minus") {
            qtde--;
        } else {
            qtde++;
        }
        if (qtde < 0) {
            qtde = 0;
        }
        $('#' + id_input).val(qtde);
    });
    function escolheMesa(num) {
        //limpa os selected, coloca apenas no clicado e muda o contador sidebar
        $('.box-mesa').removeClass('selected');
        $('#box-mesa-' + num).addClass('selected');
        $('.mesa-contador').html(num);
        $('#conteudo-mesa').attr('data-mesa',num);
        navItem('produtos');
    }
    function navItem(item) {
        //oculta todos os conteudos, exibe apenas o conteudo do item e seleciona a navegacao do item
        $('#main-content .conteudo').hide();
        $('#conteudo-' + item).show();
        $('.nav-item.active').removeClass('active');
        $('#nav-item-' + item).addClass('active');
    }
    function floatEmReais(num){
        return num.toFixed(2).replace('.',',');
    }
    //qualquer botao q tenha o atributo data-url
    $('.btn[data-url]').on('click',function(){
        var url = $(this).attr('data-url');
        if(url.length > 0){
            window.location = url;
        }
    });
</script>
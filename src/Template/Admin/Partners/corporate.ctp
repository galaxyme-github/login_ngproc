<?php $this->assign('title', $partner->name);?>

<?=$this->Form->create($partner)?>

<div id="container" class="container-fluid">
    <div class="row">
        <div class="col-xs-12 col-md-12 user-tabs">
            <a class="btn aba-edit" id="dados" href="<?=$this->Url->build(['controller' => 'Partners', 'action' => 'edit', $id])?>">
                <h6 class="text-capitalize">Dados</h6>
            </a>
            <a class="btn aba-edit" id="financeiro" href="<?=$this->Url->build(['controller' => 'Partners', 'action' => 'finance', $id])?>">
                <h6 class="text-capitalize">Financeiro</h6>
            </a>
            <a class="btn aba-edit" id="cotacoes" href="#">
                <h6 class="text-capitalize">Cotações</h6>
            </a>
            <a class="btn aba-edit" id="corporate" href="<?=$this->Url->build(['controller' => 'Partners', 'action' => 'corporate', $id])?>">
                <h6 class="text-capitalize" style="color: #004268;font-weight: bold;">Corporate</h6>
            </a>
        </div>
    </div>
</div>

<div id="main" class="container-fluid">
    <img src="<?=$this->Url->build('img/image-partner.png')?>" alt="user-image" class="user-image2" id="image_user">
</div>

<div class="container-fluid container-partner-form">
    <div id="dados-bancarios">
        <div class="form-row">
            <div class="row" style="padding:0 20px">
                <div class=" form-group col-sm-12">
                    <div class="row"><label for="txt-qualificacoes-parceiro">Resumo de qualificações</label></div>
                    <div class="row"><p>Lorem ipsum dolor sit amet consectetur, adipisicing elit. Qui odio quae delectus, blanditiis culpa tempora nostrum asperiores. Exercitationem molestiae corrupti doloribus quidem fugit iure animi, vero rerum minima maiores voluptatem?</p></div>
                </div>
            </div>
            <div class="row" style="padding:0 20px">
                <div class="form-group col-md-12">
                <div class="row"><label for="txt-qualificacoes-parceiro">Curriculum anexado</label></div>
                    <?php 
                    $anexos = ["curriculum-juan-cleiton.pdf"];
                    foreach ($anexos as $anexo) : ?>
                        <div class="row">
                            <!-- <a href="<?= $this->Url->build("/uploads/cotations/{$anexo->name}") ?>" download="<?= $anexo->name_original ?>"><?= $anexo->name_original ?></a> -->
                            <a href="<?= $this->Url->build("/uploads/cotations/{$anexo}") ?>" download="<?= $anexo ?>"><?= $anexo ?></a>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
        <div class="form-row">
            <button type="button" class="btn btn-success col-md-2" style="margin: 20px 10px 20px 0">aceitar</button>
            <button type="button" class="btn btn-danger col-md-2 " style="margin: 20px 0">recusar</button>
        </div>
    </div>
</div>
<!-- jQuery 3 -->
<script src="<?=$this->Url->build('bower_components/jquery/dist/jquery.min.js')?>"></script>
<!-- jQuery Mask Plugin -->
<script src="<?=$this->Url->build('bower_components/jQuery-Mask-Plugin-master/dist/jquery.mask.min.js')?>"></script>
<!-- Sweetalert -->
<script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>

<script>
    //checa se o campo tem somente numeros
    const bancoDados = {
        bank_name: "<?=$partner->bank_name?>",
        bank_agency: "<?=$partner->bank_agency?>",
        bank_account: "<?=$partner->bank_account?>",
    };
    let checkNumber = function () {
        let value = $(this).val();
        if (value.trim() !== "") {
            var regra = /^[0-9]+$/;
            if (!value.match(regra)) {
                swal("Permitido somente números");

                let id = $(this).attr('id');
                id = id.replace('user-', '');
                if (bancoDados[id]) {
                    $(this).val(bancoDados[id]);
                } else {
                    $(this).val('');
                }
            }
        }
    };
    $('#user-bank_name').blur(checkNumber);
    $('#user-bank_agency').blur(checkNumber);
    $('#user-bank_account').blur(checkNumber);

    //checa se o campo contem apenas letras
    let checkLetter = function () {
            let value = $(this).val();
            if (value.trim() !== "") {
                var regra = /^[A-Za-záàâãéèêíïóôõöúçñÁÀÂÃÉÈÍÏÓÔÕÖÚÇÑ'\s]+$/;
                if (!value.match(regra)) {
                    swal("Permitido somente letras");
                    $(this).val('');
                }
            }
    };
    $('#user-bank_username').blur(checkLetter);

    function maiuscula(z){
            v = z.value.toUpperCase();
            z.value = v;
    }
    //Validação de CPF
    /* Valida CPF jQuery */
    jQuery.fn.validacpf = function(){
        this.change(function(){
        CPF = jQuery(this).val();
        if(!CPF){ return false;}
        erro  = new String;
        cpfv  = CPF;
        if(cpfv.length == 14 || cpfv.length == 11){
            cpfv = cpfv.replace('.', '');
            cpfv = cpfv.replace('.', '');
            cpfv = cpfv.replace('-', '');

            var nonNumbers = /\D/;

            if(nonNumbers.test(cpfv)){
                erro = "A verificacao de CPF suporta apenas números!";
            }else{
                if (cpfv == "00000000000" ||
                    cpfv == "11111111111" ||
                    cpfv == "22222222222" ||
                    cpfv == "33333333333" ||
                    cpfv == "44444444444" ||
                    cpfv == "55555555555" ||
                    cpfv == "66666666666" ||
                    cpfv == "77777777777" ||
                    cpfv == "88888888888" ||
                    cpfv == "99999999999") {

                    erro = "Número de CPF inválido!";
                    swal(erro);
                }
                var a = [];
                var b = new Number;
                var c = 11;

                for(i=0; i<11; i++){
                    a[i] = cpfv.charAt(i);
                    if (i < 9) b += (a[i] * --c);
                }
                if((x = b % 11) < 2){
                    a[9] = 0
                }else{
                    a[9] = 11-x
                }
                b = 0;
                c = 11;
                for (y=0; y<10; y++) b += (a[y] * c--);

                if((x = b % 11) < 2){
                    a[10] = 0;
                }else{
                    a[10] = 11-x;
                }
                if((cpfv.charAt(9) != a[9]) || (cpfv.charAt(10) != a[10])){
                    erro = "Número de CPF inválido.";
                    swal(erro);
                }
            }
        }else{
            if(cpfv.length == 0){
                return false;
            }else{
                erro = "Número de CPF inválido.";
                swal(erro);
            }
        }
        if (erro.length > 0){
            jQuery(this).val('');
            jQuery('.cpf_box').append("<div style='font-size:12px; color:red;'>"+erro+"</div>");
            setTimeout(function(){jQuery(this).focus();},100);
            return false;
        }
        return jQuery(this);
    });
        }
    jQuery("#user-bank_cpf").validacpf();
</script>


<?=$this->Form->end()?>
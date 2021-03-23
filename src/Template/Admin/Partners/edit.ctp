<?php $this->assign('title', $partner->name);?>

<?=$this->Form->create($partner)?>

<div id="container" class="container-fluid">
    <div class="row">
        <div class="col-xs-12 col-md-12 user-tabs">
            <a class="btn aba-edit" id="dados" href="#">
                <h6 class="text-capitalize" style="color: #004268;font-weight: bold;">Dados</h6>
            </a>
            <a class="btn aba-edit" id="financeiro" href="<?=$this->Url->build(['controller' => 'Partners', 'action' => 'finance', $id])?>">
                <h6 class="text-capitalize">Financeiro</h6>
            </a>
            <a class="btn aba-edit" id="cotacoes" href="#">
                <h6 class="text-capitalize">Cotações</h6>
            </a>
            <a class="btn aba-edit" id="corporate" href="<?=$this->Url->build(['controller' => 'Partners', 'action' => 'corporate', $id])?>">
                <h6 class="text-capitalize">Corporate</h6>
            </a>
        </div>
    </div>
</div>

<div id="main" class="container-fluid">
    <img src="<?=$this->Url->build('img/image-partner.png')?>" alt="user-image" class="user-image2" id="image_user">
</div>

<div class="row partner-form" id="partner-form">
    <div class="form-row">
        <div class="form-group col-md-6">
            <label for="name">Nome completo</label>
            <input type="text" class="form-control" id="user-name" name="name" onkeyup="maiuscula(this)" value="<?=$partner->name?>">
        </div>
        <div class="form-group col-md-3">
            <label>CPF</label>
            <input type='text' class='form-control' id="user-cpf" name="doc_cpf" value="<?=$partner->doc_cpf?>"/>
        </div>
        <div class="form-group col-md-3">
            <label>RG</label>
            <input type='text' class='form-control'id="doc_rg" name="doc_rg" value="<?=$partner->doc_rg?>"/>
        </div>
    </div>

    <div class="form-row">
        <div class="form-group col-md-3">
            <label>Telefone</label>
            <input type="text" class="form-control phone_with_ddd" id="user-telephone" name="telephone" value="<?=$partner->telephone?>">
        </div>
        <div class="form-group col-md-3">
            <label>Celular</label>
            <input type='text' class='form-control cellphone_with_ddd' id="user-cellphone" name="cellphone" pattern=".{14,}"   required title="Campo celular incompleto" value="<?=$partner->cellphone?>" />
        </div>
        <div class="form-group col-md-6">
            <label>Email</label>
            <input type='text' class='form-control'id="user-email" name="email" value="<?=$partner->email?>"/>
        </div>
    </div>
    <div class="form-row">
            <div class="form-group col-md-4">
                <label>Nível de inglês</label>
                <select class="form-control" id="ingles-nivel" name="english_level">
                    <option value="0" <?=$partner->english_level == 0 ? 'selected' : ''?>>Básico</option>
                    <option value="1" <?=$partner->english_level == 1 ? 'selected' : ''?>>Intermediário</option>
                    <option value="2" <?=$partner->english_level == 2 ? 'selected' : ''?>>Fluente</option>
                </select>
            </div>
            <div class="form-group col-md-4">
            <label>Anos de experiência em Compras</label>
                <select class="form-control" id="exp-compras" name="purchasing_exp">
                    <option value="0"<?=$partner->english_level == 0 ? 'selected' : ''?>>Não tenho experiência</option>
                    <?php
for ($i = 1; $i <= 100; $i++):
    $selected = $partner->purchasing_exp == $i ? 'selected' : '';
    echo "<option value=\"{$i}\" {$selected}>{$i}</option>";
endfor;?>
                </select>
            </div>
            <div class="form-group col-md-4">
                <label>Categoria de atuação</label>
                <select class="form-control" id="cat-atuacao" name="acting_cat">
                    <option value="0" <?=$partner->acting_cat == 0 ? 'selected' : ''?>>Todas as categorias</option>
                    <option value="29" <?=$partner->acting_cat == 29 ? 'selected' : ''?>>Nenhuma das Listadas</option>
                    <option value="1" <?=$partner->acting_cat == 1 ? 'selected' : ''?>>Apps e Jogos</option>
                    <option value="2" <?=$partner->acting_cat == 2 ? 'selected' : ''?>>Bebês</option>
                    <option value="3" <?=$partner->acting_cat == 3 ? 'selected' : ''?>>Beleza</option>
                    <option value="4" <?=$partner->acting_cat == 4 ? 'selected' : ''?>>Bolsas, Malas e Mochila</option>
                    <option value="5" <?=$partner->acting_cat == 5 ? 'selected' : ''?>>Brinquedos e Jogos</option>
                    <option value="6" <?=$partner->acting_cat == 6 ? 'selected' : ''?>>Casa</option>
                    <option value="7" <?=$partner->acting_cat == 7 ? 'selected' : ''?>>CD e Vinil</option>
                    <option value="8" <?=$partner->acting_cat == 8 ? 'selected' : ''?>>Computadores e Informática</option>
                    <option value="9" <?=$partner->acting_cat == 9 ? 'selected' : ''?>>Cozinha</option>
                    <option value="30" <?=$partner->acting_cat == 30 ? 'selected' : ''?>>Material de Limpeza</option>
                    <option value="10" <?=$partner->acting_cat == 10 ? 'selected' : ''?>>DVD e Blu-Ray</option>
                    <option value="11" <?=$partner->acting_cat == 11 ? 'selected' : ''?>>Eletrodomésticos</option>
                    <option value="12" <?=$partner->acting_cat == 12 ? 'selected' : ''?>>Eletrônicos</option>
                    <option value="13" <?=$partner->acting_cat == 13 ? 'selected' : ''?>>Esporte e Aventura</option>
                    <option value="14" <?=$partner->acting_cat == 14 ? 'selected' : ''?>>Ferramentas e Materiais de Construção</option>
                    <option value="15" <?=$partner->acting_cat == 15 ? 'selected' : ''?>>Games</option>
                    <option value="16" <?=$partner->acting_cat == 16 ? 'selected' : ''?>>Jardim e Piscina</option>
                    <option value="17" <?=$partner->acting_cat == 17 ? 'selected' : ''?>>Livros</option>
                    <option value="18" <?=$partner->acting_cat == 18 ? 'selected' : ''?>>Material de Escritório e Papelaria</option>
                    <option value="19" <?=$partner->acting_cat == 19 ? 'selected' : ''?>>Móveis e Decoração</option>
                    <option value="20" <?=$partner->acting_cat == 20 ? 'selected' : ''?>>Roupas, Calçados e Joias</option>
                    <option value="21" <?=$partner->acting_cat == 21 ? 'selected' : ''?>>Saúde e Cuidados Pessoais</option>
                    <option value="22" <?=$partner->acting_cat == 22 ? 'selected' : ''?>>Lista Compras Supermercado</option>
                    <option value="23" <?=$partner->acting_cat == 23 ? 'selected' : ''?>>Médico Hospitalar</option>
                    <option value="24" <?=$partner->acting_cat == 24 ? 'selected' : ''?>>Turismo</option>
                    <option value="25" <?=$partner->acting_cat == 25 ? 'selected' : ''?>>Químico</option>
                    <option value="26" <?=$partner->acting_cat == 26 ? 'selected' : ''?>>MRO</option>
                    <option value="27" <?=$partner->acting_cat == 27 ? 'selected' : ''?>>Clube de Assinaturas</option>
                    <option value="28" <?=$partner->acting_cat == 28 ? 'selected' : ''?>>Cursos Online</option>
                </select>
            </div>
        </div>
    <div class="form-row">
        <div class="form-group col-md-6">
            <label>Cep</label>
            <input type="text" class="form-control cep" id="user-address_zipcode" name="address_zipcode" value="<?=$partner->address_zipcode?>">
        </div>
        <div class="form-group col-md-3">
            <label>Rua</label>
            <input type='text' class='form-control' id="user-address_street" name="address_street" value="<?=$partner->address_street?>" />
        </div>
        <div class="form-group col-md-3">
            <label>Número</label>
            <input type='text' class='form-control'id="user-address_number" name="address_number" value="<?=$partner->address_number?>" />
        </div>
    </div>
    <div class="form-row">
        <div class="form-group col-md-6">
            <label>Complemento</label>
            <input type="text" class="form-control" id="user-address_complement" name="address_complement" value="<?=$partner->address_complement?>">
        </div>
        <div class="form-group col-md-3">
            <label>Cidade</label>
            <input type='text' class='form-control' id="user-address_city" name="address_city" value="<?=$partner->address_city?>" />
        </div>
        <div class="form-group col-md-3">
            <label>UF</label>
            <input type='text' class='form-control'id="user-address_uf" name="address_uf" value="<?=$partner->address_uf?>" />
        </div>
    </div>

    <div class="form-row">
        <div class="form-group col-md-6">
            <label>Bairro</label>
            <input type="text" class="form-control" id="user-address_neighborhood" name="address_neighborhood" value="<?=$partner->address_neighborhood?>">
        </div>
        <div class="form-group col-md-6">
            <label>Data de Nascimento</label>
            <input type='text' class='form-control date' id="user-birth_date" name="birth_date" placeholder="dd/mm/aaaa" value="<?=$partner->birth_date ? $partner->birth_date->format('d/m/Y') : '';?>" />
        </div>
    </div>

    <div class="form-row">
        <div class="form-group col-md-6">
            <label>Senha</label>
            <input type="password" class="form-control" id="user-password" name="password" placeholder="Cadastrar nova senha">
        </div>
        <div class="form-group col-md-6">
            <label>Confirmar Senha</label>
            <input type='password' class='form-control' id="user-confirm-password" name="confirm-password"/>
        </div>
    </div>
    <div class="form-row" style="display: none;" id="password-rule-info">
        <span style="font-size: 13px; color: #ff0000;    margin-left: 20px;">A senha deve conter no mínimo 6 caracteres: sendo ao menos 1 letra maiúscula, 1 letra minúscula e 1 símbolo</span>
    </div>
    <div class="form-row">
    <button type="submit" class="btn btn-success col-md-2 btn-edit2">Salvar</button>
    <button type="button" id="btn-banir" class="btn btn-danger col-md-2 btn-edit2">Banir</button>
    </div>




<!-- jQuery 3 -->
<script src="<?=$this->Url->build('bower_components/jquery/dist/jquery.min.js')?>"></script>
<!-- jQuery Mask Plugin -->
<script src="<?=$this->Url->build('bower_components/jQuery-Mask-Plugin-master/dist/jquery.mask.min.js')?>"></script>
<!-- Sweetalert -->
<script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>

<script>
    $(document).ready(function(){
        $('.date').mask('00/00/0000');
        $('.cep').mask('00000-000');
        $('.phone_with_ddd').mask('(00) 0000-0000');
        $('.cellphone_with_ddd').mask('(00) 00000-0000');
        $('.placeholder').mask("00/00/0000", {placeholder: "__/__/____"});
        $('.selectonfocus').mask("00/00/0000", {selectOnFocus: true});
    });
    // Checa se o e-mail é valido
    $('#user-email').blur(function () {
        let str = $(this).val();
        var re = /^(([^<>()[\]\\.,;:\s@\"]+(\.[^<>()[\]\\.,;:\s@\"]+)*)|(\".+\"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
        if(!re.test(str)){
            swal("Por favor informe um e-mail válido");
            $(this).val('');
        }
    });

    // Checa se a data de nascimento é valida
    $('#user-birth_date').blur(function () {
        let str = $(this).val();
        var regex = /^(?:(?:31(\/|-|\.)(?:0?[13578]|1[02]))\1|(?:(?:29|30)(\/|-|\.)(?:0?[13-9]|1[0-2])\2))(?:(?:1[6-9]|[2-9]\d)?\d{2})$|^(?:29(\/|-|\.)0?2\3(?:(?:(?:1[6-9]|[2-9]\d)?(?:0[48]|[2468][048]|[13579][26])|(?:(?:16|[2468][048]|[3579][26])00))))$|^(?:0?[1-9]|1\d|2[0-8])(\/|-|\.)(?:(?:0?[1-9])|(?:1[0-2]))\4(?:(?:1[6-9]|[2-9]\d)?\d{2})$/;
        if(!regex.test(str)){
            swal("Por favor informe uma data válida");
            $(this).val('');
        }
    });

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
    $('#user-name').blur(checkLetter);

    function maiuscula(z){
            v = z.value.toUpperCase();
            z.value = v;
    }
    $(document).ready(function() {
        $('#btn-banir').click(function() {
            swal({
                title: "Tem certeza?",
                text: "Uma vez banido, o usuário não terá mais acesso ao sistema.",
                icon: "warning",
                buttons: true,
                dangerMode: true,
            })
            .then((confirm) => {
                if (confirm) {
                    window.location.href = "<?=$this->Url->build(['controller' => 'Partners', 'action' => 'banir', $id])?>";
                    swal("Usuário banido com sucesso", {
                        icon: "success",
                    });
                } else {
                    return;
                }
            });
        })
    });
    // validação do cadastramento de senha: 1 maiusculo 1 minuscula e 1 caractere especial
    $('#user-password').blur(function () {
        let senha = $(this).val();
        let regex = /^(?=^.{6,}$)((?=.*\W+))(?![.\n])(?=.*[A-Z])(?=.*[a-z]).*$/g;

        if(!regex.test(senha)) {
            //alert("A senha deve conter no mínimo 6 caracteres: sendo ao menos 1 letra maiúscula, 1 letra minúscula e 1 símbolo");
            $('#password-rule-info').show();
            this.value = '';
        } else {
            $('#password-rule-info').hide();
        }
    })
    //Digitar o cep e preencher endereço automaticamente
    $("#user-address_zipcode").focusout(function(){
        //Início do Comando AJAX
		$.ajax({
			//O campo URL diz o caminho de onde virá os dados
			//É importante concatenar o valor digitado no CEP
			url: 'https://viacep.com.br/ws/'+$(this).val()+'/json/unicode/',
			//Aqui você deve preencher o tipo de dados que será lido,
			//no caso, estamos lendo JSON.
			dataType: 'json',
			//SUCESS é referente a função que será executada caso
			//ele consiga ler a fonte de dados com sucesso.
			//O parâmetro dentro da função se refere ao nome da variável
			//que você vai dar para ler esse objeto.
			success: function(resposta){
				//Agora basta definir os valores que você deseja preencher
				//automaticamente nos campos acima.
				$("#user-address_street").val(resposta.logradouro);
				$("#user-address_complement").val(resposta.complemento);
				$("#user-address_neighborhood").val(resposta.bairro);
				$("#user-address_city").val(resposta.localidade);
				$("#user-address_uf").val(resposta.uf);
				//Vamos incluir para que o Número seja focado automaticamente
				//melhorando a experiência do usuário
				$("#user-address_number").focus();
			}
		});
	});
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
    jQuery("#user-cpf").validacpf();

    //valida se já há alguem com o mesmo cpf
    $('#user-cpf').blur(function () {
        let cpf = $('#user-cpf').val();
        $.get(`/users/check-cpf/${cpf}`, function (data) {
            let exist = data.exists;
            if (exist) {
                swal("CPF já cadastrado em nosso sistema", "Informe um cpf que não tenha cadastro conosco.")
                $('#user-cpf').val('');
            }
        });
    });

</script>


<?=$this->Form->end()?>




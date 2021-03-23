<?php $this->assign('title', 'Meu perfil');?>

<?=$this->Form->create($user, ['type' => 'file'])?>
<?php

/*
Menu
 */
?>

<div id="container" class="container-fluid container-edit-partner">
    <div class="row">
        <div class="titulo-perfil">Dados</div>
    </div>
</div>
<?php

/*
------ imagem do user--------
 */
?>
<div id="main" class="container-fluid edit-partner-title">
<div class="row">
    <div class="box-dropify col-1">
    <input type="file" class="dropify" 
            id="upload-profile"
            name="nova_imagem"
            data-default-file="<?=$user->getProfileUrl()?>"  
            data-height="160" 
            data-max-file-size="5m"  
            data-min-width="160" 
            data-max-width="800" 
            data-min-height="160" 
            data-max-height="800" 
            data-show-loader="false" 
            data-allowed-formats="portrait square" 
            allowedFileExtensions="png jpg jpeg" 
            data-errors-position="outside"
            alt="Sua foto de perfil" 
        />
        <!--O input abaixo serve para informar se o parceiro removeu ou não sua imagem de perfil-->
        <input type="number" id="removedImageProfile" name="removedImageProfile" style="display:none" value="0">
    </div>
    <div class="col-11">
         <!-- Gabi pediu para retirar esta div por enquanto -->
    <!-- <div class='col-md-3 dados-bancarios'>
        <div class="title-clientpagamentos">Formas de pagamento</div>
        <div>
            <button type="button" class="col-xs-9 btn ver-dadosbancarios">Ver</button>
        </div>
    </div> -->
    </div>
</div>
</div>
<?php
/*
-----corpo da pagina layout4-----

contendo um formulário

 */
?>

<div class="container-fluid row partner-form" id="partner-form">
    <div class="form-row">
        <?php if ($user->doc_cpf != ""): ?>
        <div class="form-group col-md-6">
            <label for="name">Nome completo</label>
            <input type="text" class="form-control" id="user-name" name="name" value="<?=$user->name?>" readonly="readonly">
        </div>
        <div class="form-group col-md-3">        
            <label>CPF </label>
            <input type='text' class='form-control' id="user-cpf" name="doc_cpf" value="<?=$user->doc_cpf?>" readonly="readonly"/>           
        </div>
        <div class="form-group col-md-3">
            <label>RG</label>
            <input type='text' class='form-control'id="doc_rg" name="doc_rg" value="<?=$user->doc_rg?>"/>
        </div>
        <?php else: ?>
            <div class="form-group col-md-6">
                <label for="name">Razão Social/Nome Fantasia</label>
                <input type="text" class="form-control" id="user-name" name="name" value="<?=$user->name?>" readonly="readonly">
            </div>
            <div class="form-group col-md-6">  
                <label>CNPJ</label>
                <input type='text' class='form-control' id="user-cnpj" name="doc_cnpj" value="<?=$user->doc_cnpj?>" readonly="readonly"/>
            </div>
        <?php endif;?>    
    </div>

    <div class="form-row">
        <div class="form-group col-md-3">
            <label>Telefone</label>
            <input type="text" class="form-control phone_with_ddd" id="user-telephone" name="telephone" onsubmit="retiraMascara()"   value="<?=$user->telephone?>">
        </div>
        <div class="form-group col-md-3">
            <label>Celular</label>
            <input type='text' class='form-control cellphone_with_ddd' id="user-cellphone" name="cellphone" pattern=".{14,}" onsubmit="retiraMascara()"  required title="Campo celular incompleto" value="<?=$user->cellphone?>" />
        </div>
        <div class="form-group col-md-6">
            <label>Email</label>
            <input type='text' class='form-control'id="user-email" name="email" value="<?=$user->email?>"/>
        </div>
    </div>

    <div class="form-row">
        <div class="form-group col-md-6">
            <label>Cep</label>
            <input type="text" class="form-control cep" id="user-address_zipcode" name="address_zipcode" value="<?=$user->address_zipcode?>">
        </div>
        <div class="form-group col-md-3">
            <label>Rua</label>
            <input type='text' class='form-control' id="user-address_street" name="address_street" value="<?=$user->address_street?>" />
        </div>
        <div class="form-group col-md-3">
            <label>Número</label>
            <input type='text' class='form-control'id="user-address_number" name="address_number" value="<?=$user->address_number?>" />
        </div>
    </div>
    <div class="form-row">
        <div class="form-group col-md-6">
            <label>Complemento</label>
            <input type="text" class="form-control" id="user-address_complement" name="address_complement" value="<?=$user->address_complement?>">
        </div>
        <div class="form-group col-md-3">
            <label>Cidade</label>
            <input type='text' class='form-control' id="user-address_city" name="address_city" value="<?=$user->address_city?>" />
        </div>
        <div class="form-group col-md-3">
            <label>UF</label>
            <input type='text' class='form-control'id="user-address_uf" name="address_uf" value="<?=$user->address_uf?>" />
        </div>
    </div>

    <div class="form-row">
        <div class="form-group col-md-6">
            <label>Bairro</label>
            <input type="text" class="form-control" id="user-address_neighborhood" name="address_neighborhood" value="<?=$user->address_neighborhood?>">
        </div>
        <div class="form-group col-md-6">
            <label>Data de Nascimento</label>
            <input type='text' class='form-control date' id="user-birth_date" name="birth_date" value="<?=$user->birth_date ? $user->birth_date->format('d/m/Y') : '';?>" />
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
    </div>


<!-- jQuery 3 -->
<script src="<?=$this->Url->build('bower_components/jquery/dist/jquery.min.js')?>"></script>
<!-- jQuery Mask Plugin -->
<script src="<?=$this->Url->build('bower_components/jQuery-Mask-Plugin-master/dist/jquery.mask.min.js')?>"></script>
<!-- Sweetalert -->
<script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
<!--Dropify-->
<script src="<?=$this->Url->build('plugins/dropify/dist/js/dropify.js')?>"></script>
<script>
//Iniciando plugin dropify
$('.dropify').dropify({
    messages: {
        'default': 'Arraste e solte um arquivo ou clique',
        'replace': 'Arraste e solte ou clique para substituir',
        'remove':  'Remover',
        'error':   'Ops, algo de errado ocorreu.'
    },
    error: {
        'fileSize': 'O tamanho do arquivo é muito grande ({{ value }} Máximo).',
        'minWidth': 'A largura da imagem é muito pequena ({{ value }}px Mínimo).',
        'maxWidth': 'A largura da imagem é muito grande ({{ value }}px Máximo).',
        'minHeight': 'A altura da imagem é muito pequena ({{ value }}px Mínimo).',
        'maxHeight': 'A altura da imagem é muito grande ({{ value }}px Máximo).',
        'imageFormat': 'O formato da imagem não é permitido (Apenas {{ value }}).'
    },
    tpl: {
        wrap:            '<div class="dropify-wrapper"></div>',
        loader:          '<div class="dropify-loader"></div>',
        message:         '<div class="dropify-message"><span class="file-icon" /> <p>{{ default }}</p></div>',
        preview:         '<div class="dropify-preview"><span class="dropify-render"></span><div class="dropify-infos"><div class="dropify-infos-inner"><p class="dropify-infos-message">{{ replace }}</p></div></div></div>',
        filename:        '',
        clearButton:     '<button type="button" class="dropify-clear" style="top: 20%;right: 25.5%;">{{ remove }}</button>',
        errorLine:       '<p class="dropify-error">{{ error }}</p>',
        errorsContainer: '<div class="dropify-errors-container"><ul></ul></div>'
    }
});
</script>

<script>

    $('button[type=button].dropify-clear').click(function(){
        //Removeu e deixou sem imagem
        $('#removedImageProfile').val(1);
    });

    $('#upload-profile').click(function(){
        //Alterou por outra
        $('#removedImageProfile').val(0);
    });
</script>

<script>
    $(document).ready(function(){
        $('.date').mask('00/00/0000');
        $('.cep').mask('00000-000');
        $('.phone_with_ddd').mask('(00) 0000-0000');
        $('.cellphone_with_ddd').mask('(00) 00000-0000');
        $('.placeholder').mask("00/00/0000", {placeholder: "__/__/____"});

        $('.selectonfocus').mask("00/00/0000", {selectOnFocus: true});
    });

    //retira mascaras
    function retiraMascara() {
        let campo = this.value;
        return campo.value.replace(/\D/g, '');
    }

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
</script>





<?=$this->Form->end()?>
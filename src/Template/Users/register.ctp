<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>NGProc</title>
    <!-- Tell the browser to be responsive to screen width -->
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
    <!-- Bootstrap 3.3.7 -->
    <link rel="stylesheet" href="<?= $this->Url->build('bower_components/bootstrap/dist/css/bootstrap.min.css') ?>">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="<?= $this->Url->build('bower_components/font-awesome/css/font-awesome.min.css') ?>">
    <!-- Ionicons -->
    <link rel="stylesheet" href="<?= $this->Url->build('bower_components/Ionicons/css/ionicons.min.css') ?>">
    <!-- Theme style -->
    <link rel="stylesheet" href="<?= $this->Url->build('dist/css/AdminLTE.min.css') ?>">

    <!-- Multiselect -->
    <link href="https://cdn.jsdelivr.net/npm/select2@4.0.12/dist/css/select2.min.css" rel="stylesheet" />

    <!-- iCheck -->
    <link rel="stylesheet" href="<?= $this->Url->build('plugins/iCheck/square/blue.css') ?>">

    <?= $this->Html->css('login_custom'); ?>

    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
  <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
  <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
  <![endif]-->

    <!-- Google Font -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,600,700,300italic,400italic,600italic">

    <style>
        /*ESTILIZANDO O MULTISELECT (PLUGIN SELECT2) DO CADASTRO DE PARCEIROS*/
        /* Caixa de seleção */
        .select2-container--default .select2-selection--multiple {
            border-radius: 36px;
            width: 100%;
            max-height: 68px;
            overflow-y: auto;

        }

        /* Barra de rolagem da caixa de seleção */
        .select2-container--default .select2-selection--multiple::-webkit-scrollbar {
            display: none;
        }

        /* Caixa de seleção em foco*/
        .select2-container--default.select2-container--focus .select2-selection--multiple {
            border-radius: 36px;
            padding: 5px 10px;
        }

        /*Item escolhido localizado dentro da caixa de seleção*/
        .select2-container--default .select2-selection--multiple .select2-selection__choice {
            background-color: #367fa9;
        }

        /*Item escolhido marcado dentro da lista de seleção*/
        .select2-container--default .select2-results__option[aria-selected=true] {
            background-color: #367fa9;
            color: #fff;
        }

        /*Item ao passar o mouse por cima*/
        .select2-container--default .select2-results__option--highlighted[aria-selected] {
            background-color: #367fa9;
        }

        /* botão de excluir o item selecionado */
        .select2-container--default .select2-selection--multiple .select2-selection__choice__remove {
            color: #fff;
        }
    </style>
</head>

<body class="hold-transition login-page">

    <input type="hidden" id="terms_of_use" name="terms_of_use">

    <div class="login-container">
        <div class="row">
            <div class="col-md-6 mobile">
                <div class="login-banner img-fluid" style="background-image: url('<?=$this->Url->build('img/Imagem-logo-login.png')?>');"></div>
            </div>
            <div class="col-md-6 etapa1" id="etapa1" style="display:block">
                <!-- /.login-logo -->
                <div class="login-box-body">
                    <h1>Vamos começar?</h1>
                    <div class="linha-titulo"></div>
                    <?= $this->Form->create('Users', ['action' => 'register','id' => 'form-register']) ?>
                    <div class="register-subtitle">
                        Em qual perfil você se encaixa?
                    </div>
                    <div class="opcoes-cliente">
                        <!-- Cliente Antônio pediu para tirar a opção cliente, por enquanto -->
                        <input type="radio" id="cliente-radio" name="role" value="1" <?php if ($this->request->getCookie('direct_link') == '1') echo 'checked="checked"'; ?>> Cliente<br><br>
                        <input type="radio" id="parceiro-radio" name="role" value="2"> Parceiro<br>
                    </div>
                    <button type="button" id="button-et1" class="btn btn-primary btn-block btn-flat btn-login-custom btn-avancar">Avançar</button>

                    <hr />
                    <div style="text-align: center;margin-top: -9px">
                        <a href="<?= $this->Url->build(['action' => 'login']) ?>"  class="text-center" style="text-decoration: underline">Já tem uma conta?</a>
                    </div>
                </div>
            </div>

            <!-- 1ª TELA - SELECIONAR TIPO DE USUÁRIO -->
            <div class="col-md-6 etapa2" id="etapa2" style="display:none">
                <!-- /.login-logo -->
                <div class="login-box-body">
                    <h1>Vamos começar?</h1>
                    <div class="linha-titulo"></div>
                    <div class="register-subtitle">
                        Qual tipo de inscrição?
                    </div>
                    <div class="form-etapa2" id="form-etapa2" style="margin: 10px">
                        <div class="form-group has-feedback">
                            <input type="radio" name="tpdoc" value="1" <?php if ($this->request->getCookie('direct_link') == '1') echo 'checked="checked"'; ?>> Pessoa Física <br /><br />
                            <input type="radio" name="tpdoc" value="2"> Pessoa Jurídica
                            <button type="button" id="button-et2" class="btn btn-primary btn-block btn-flat btn-login-custom btn-avancar">Avançar</button>
                        </div>
                    </div>
                    <hr />
                    <div style="text-align: center;margin-top: -9px">
                        <a href="<?= $this->Url->build(['action' => 'login']) ?>" class="text-center" style="text-decoration: underline" >Já tem uma conta?</a>
                    </div>
                </div>

            </div>

            <!-- 2ª TELA - SE FOR CLIENTE - PERGUNTA O TIPO DA PESSOA -->
            <div class="col-md-6 etapa3" id="etapa3" style="display:none">
                <!-- /.login-logo -->
                <div class="login-box-body">
                    <h1>Vamos começar?</h1>
                    <div class="linha-titulo"></div>
                    <div class="register-subtitle">
                        Qual número de inscrição?
                    </div>
                    <div class="form-etapa3" id="form-etapa3">
                        <div class="form-group has-feedback">

                            <div class="form-group has-feedback">
                                <input type="text" id="form-cpf"  name="doc_cpf" class="form-control" placeholder="Digite seu CPF">
                            </div>
                            <div class="form-group has-feedback">
                                <input type="text" id="form-cnpj" name="doc_cnpj" style="display:none" class="form-control " placeholder="Digite seu CNPJ">
                            </div>
                        </div>
                        <button type="button" id="button-et3" class="btn btn-primary btn-block btn-flat btn-login-custom btn-prosseguir">Prosseguir</button>
                        <hr />
                        <div style="text-align: center;margin-top: -9px">
                            <a href="<?= $this->Url->build(['action' => 'login']) ?>" class="text-center" style="text-decoration: underline" >Já tem uma conta?</a>
                        </div>
                    </div>
                </div>
            </div>

            <!-- 3ª TELA - SELECIONAR TIPO DE USUÁRIO -->
            <div class="col-md-6 etapa4" id="etapa4" style="display:none">
                <!-- /.login-logo -->
                <div class="login-box-body">
                    <h1>Vamos começar?</h1>

                    <div class="form-etapa4" id="form-etapa4">

                        <div class="form-group has-feedback">
                            <div class="form-group has-feedback">
                                <input type="text" id="form-name" name="name" class="form-control" placeholder="Nome Completo" onkeyup="maiuscula(this)" required>
                            </div>
                            <div class="form-group has-feedback">
                                <input type="email" id="form-email" name="email" class="form-control" placeholder="Email" required>
                            </div>
                            <div class="form-group has-feedback">
                                <input type="password" id="form-password" name="password" class="form-control" placeholder="Senha" required>
                            </div>
                            <div class="form-group has-feedback">
                                <input type="password" id="form-password-confirm" name="password-register-confirm" class="form-control" placeholder="Confirmar senha" required>
                            </div>
                            <div class="form-group" style="display: none;" id="password-rule-info">
                                <!-- <span style="font-size: 13px; color: #ff0000;">A senha deve conter no mínimo 6 caracteres: sendo ao menos 1 letra maiúscula, 1 letra minúscula e 1 símbolo</span> -->
                                <span style="font-size: 13px; color: #ff0000;">A senha deve conter no mínimo 6 caracteres.</span>
                            </div>
                        </div>
                        <button type="button" id="button-et4" class="btn btn-primary btn-block btn-flat btn-login-custom btn-prosseguir">Prosseguir</button>
                        <hr />
                        <div style="text-align: center;margin-top: -9px">
                            <a href="<?= $this->Url->build(['action' => 'login']) ?>" class="text-center" style="text-decoration: underline"  >Já tem uma conta?</a>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-6 etapa5" id="etapa5" style="display:none">
                <!-- /.login-logo -->
                <div class="login-box-body">
                    <h1>Nos conte mais sobre você!</h1>

                    <div class="form-etapa5">
                        <div class="form-group has-feedback">
                            <h5 class="text-muted m-0">Nível de Inglês</h5>
                            <select class="form-control" id="ingles-nivel" name="english_level" placehoder="Nível de Inglês">

                                <option value="0">Básico</option>
                                <option value="1">Intermediário</option>
                                <option value="2">Fluente</option>
                            </select>
                        </div>
                        <div class="form-group has-feedback">
                            <h5 class="text-muted m-0">Anos de experiência em Compras</h5>
                            <select class="form-control" id="exp-compras" name="purchasing_exp" placehoder="Anos de experiencia em Compras">
                                <option value="0">Não tenho experiência</option>
                                <?php
                                for ($i = 1; $i <= 100; $i++) :
                                    echo "<option value=\"{$i}\">{$i}</option>";
                                endfor; ?>
                            </select>
                        </div>
                        <div class="form-group has-feedback">
                            <input type="hidden" id="acting_cat" name="acting_cat" required>
                            <h5 class="text-muted m-0">Categoria de atuação</h5>
                            <select style="width:100%;" class="form-control js-example-basic-single" id="cat-atuacao" multiple="multiple">
                                <option value="0">Todas as categorias</option>
                                <option value="29">Nenhuma das Listadas</option>
                                <option value="1">Apps e Jogos</option>
                                <option value="2">Bebês</option>
                                <option value="3">Beleza</option>
                                <option value="4">Bolsas, Malas e Mochila</option>
                                <option value="5">Brinquedos e Jogos</option>
                                <option value="6">Casa</option>
                                <option value="7">CD e Vinil</option>
                                <option value="8">Computadores e Informática</option>
                                <option value="9">Cozinha</option>
                                <option value="30">Material de limpeza</option>
                                <option value="10">DVD e Blu-Ray</option>
                                <option value="11">Eletrodomésticos</option>
                                <option value="12">Eletrônicos</option>
                                <option value="13">Esporte e Aventura</option>
                                <option value="14">Ferramentas e Materiais de Construção</option>
                                <option value="15">Games</option>
                                <option value="16">Jardim e Piscina</option>
                                <option value="17">Livros</option>
                                <option value="18">Material de Escritório e Papelaria</option>
                                <option value="19">Móveis e Decoração</option>
                                <option value="20">Roupas, Calçados e Joias</option>
                                <option value="21">Saúde e Cuidados Pessoais</option>
                                <option value="22">Lista Compras Supermercado</option>
                                <option value="23">Médico Hospitalar</option>
                                <option value="24">Turismo</option>
                                <option value="25">Químico</option>
                                <option value="26">MRO</option>
                                <option value="27">Clube de Assinaturas</option>
                                <option value="28">Cursos Online</option>
                                <option value="30">Outros</option>
                            </select>

                        </div>
                    </div>
                    <button type="button" id="buttom-et5" class="btn btn-primary btn-block btn-flat btn-login-custom btn-prosseguir-et4">Prosseguir</button>
                    <hr />
                    <div style="text-align: center;margin-top: -9px">
                        <a href="<?= $this->Url->build(['action' => 'login']) ?>" class="text-center" style="text-decoration: underline">Já tem uma conta?</a>
                    </div>
                </div>
            </div>
            <div class="col-md-6 etapa6" id="etapa6" style="display:none">
                <!-- /.login-logo -->
                <div class="login-box-body login-box-et6">
                    <h1 class="h1-et6">Dados Bancários</h1>
                    <h3 class="h3-et6">Preencha os dados para a realização de pagamentos</h3>

                    <div class="form-etapa6">
                        <div class="form-group has-feedback">
                            <input type="text" id="nome-conta" name="bank_username" class="form-control" placeholder="Nome" onkeyup="maiuscula(this)">
                        </div>
                        <div class="form-group has-feedback">
                            <input type="text" id="cpf-conta" name="bank_cpf" class="form-control" placeholder="CPF">
                        </div>
                        <div class="form-group has-feedback">
                            <input type="text" id="banco-conta" name="bank_name" class="form-control" placeholder="Banco">
                        </div>
                        <div class="form-group has-feedback">
                            <input type="text" id="agencia-conta" name="bank_agency" class="form-control" placeholder="Agência">
                        </div>
                        <div class="form-group has-feedback">
                            <input type="text" id="cc-conta" name="bank_account" class="form-control" placeholder="Conta">
                        </div>
                    </div>
                    <button type="submit" id="submit-btn5" class="btn btn-primary btn-block btn-flat btn-login-custom btn-registrar-et6">Registrar</button>
                    <?= $this->Form->end(); ?>
                    <hr />
                    <div style="text-align: center;margin-top: -9px">
                        <a href="<?= $this->Url->build(['action' => 'login']) ?>" class="text-center" style="text-decoration: underline">Ou cadastre sua conta do Paypal</a>
                    </div>
                </div>
            </div>

            <div class="col-md-6 etapa7" id="etapa7" style="display:none">
                <!-- /.login-logo -->
                <div class="login-box-body">
                    <h1>Termos de Uso</h1>
                    <div class="linha-titulo"></div>
                    <div style="width: 100%; text-align: center; padding-top: 30px">
                        <a href="/uploads/docs/termos-uso.pdf" target="_blank"><img src="/img/icons/pdf.png" alt="termos de uso" width="40px"></a>
                    </div>
                    <div class="register-subtitle">
                        Você precisa aceitar para continuar.
                    </div>
                    <div class="form-etapa7" id="form-etapa7">
                        <div class="form-group has-feedback">
                            <div class="row row-lembrarsenha">
                                <div class="col-xs-12" style="padding-left: 22px;">
                                <div class="checkbox icheck" id="">
                                    <label for="check-termos-uso" style="margin: 5px 0 20px 0">
                                        <input id="check-termos-uso"  type="checkbox"> Eu aceito os termos de uso
                                    </label>
                                </div>
                                </div>
                            </div>
                        </div>
                        <button type="button" id="button-et7" class="btn btn-primary btn-block btn-flat btn-login-custom btn-prosseguir">Concluir</button>
                        <hr />
                        <div style="text-align: center;margin-top: -9px">
                            <a href="<?= $this->Url->build(['action' => 'login']) ?>" class="text-center" style="text-decoration: underline" >Já tem uma conta?</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    
        <div class="login-box">

            <!-- /.login-box-body -->
        </div>
    </div>
    <!-- /.login-box -->

    <!-- jQuery 3 -->
    <script src="<?= $this->Url->build('bower_components/jquery/dist/jquery.min.js') ?>"></script>

    <!-- Bootstrap 3.3.7 -->
    <script src="<?= $this->Url->build('bower_components/bootstrap/dist/js/bootstrap.min.js') ?>"></script>

    <!-- Sweetalert -->
    <script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>

    <!-- Multiselect - PLUGIN SELECT2-->
    <script src="https://cdn.jsdelivr.net/npm/select2@4.0.12/dist/js/select2.min.js"></script>

    <!-- JQuery Mask Plugin -->
    <script type="text/javascript" src="<?= $this->Url->build('bower_components/jQuery-Mask-Plugin-master/dist/jquery.mask.min.js') ?>"></script>
    <!-- Máscara CPF Parceiro -->
    <script type="text/javascript">
        $(document).ready(function() {
            $("#form-cpf").mask("000.000.000-00");
            $("#form-cnpj").mask("00.000.000/0000-00");
            // Iniciando o plugin SELECT2 no elemeno que o utiliza
            $('.js-example-basic-single').select2({
                placeholder: "Categorias de interesse"
            });
        });
    </script>

    <!-- iCheck -->
    <script src="<?= $this->Url->build('plugins/iCheck/icheck.min.js') ?>"></script>

    <script>
        $(function() {
            $('input').iCheck({
                checkboxClass: 'icheckbox_square-blue',
                radioClass: 'iradio_square-blue',
                increaseArea: '20%' /* optional */
            });
        });
    </script>

    <script>
        // validação do cadastramento de senha: 1 maiusculo 1 minuscula e 1 caractere especial
        $('#form-password').blur(function() {
            let senha = $(this).val();
            //let regex = /^(?=^.{6,}$)((?=.*\W+))(?![.\n])(?=.*[A-Z])(?=.*[a-z]).*$/g;
            let regex = /^(?=^.{6,}$).*$/g;

            if (!regex.test(senha)) {
                //alert("A senha deve conter no mínimo 6 caracteres: sendo ao menos 1 letra maiúscula, 1 letra minúscula e 1 símbolo");
                $('#password-rule-info').show();
                this.value = '';
            } else {
                $('#password-rule-info').hide();
            }
        })
        //converte input para letras maiusculas
        function maiuscula(z) {
            v = z.value.toUpperCase();
            z.value = v;
        }
        // Checa se o e-mail é valido
        $('#form-email').blur(function() {
            let str = $(this).val();
            var re = /^(([^<>()[\]\\.,;:\s@\"]+(\.[^<>()[\]\\.,;:\s@\"]+)*)|(\".+\"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
            if (!re.test(str)) {
                swal("Por favor informe um e-mail válido");
                $(this).val('');
            }
        })

        //checa se o campo contem apenas letras
        let checkLetter = function() {
            let value = $(this).val();
            if (value.trim() !== "") {
                var regra = /^[A-Za-záàâãéèêíïóôõöúçñÁÀÂÃÉÈÍÏÓÔÕÖÚÇÑ'\s]+$/;
                if (!value.match(regra)) {
                    swal("Permitido somente letras");
                    $(this).val('');
                }
            }
        };

        $('#nome-conta').blur(checkLetter);
        $('#form-name').blur(checkLetter);

        //Validação de CPF/CNPJ
        /* Valida CPF jQuery */
        function validarCNPJ(cnpj) {

            cnpj = cnpj.replace(/[^\d]+/g, '');

            if (cnpj == '') return false;

            if (cnpj.length != 14)
                return false;

            // Elimina CNPJs invalidos conhecidos
            if (cnpj == "00000000000000" ||
                cnpj == "11111111111111" ||
                cnpj == "22222222222222" ||
                cnpj == "33333333333333" ||
                cnpj == "44444444444444" ||
                cnpj == "55555555555555" ||
                cnpj == "66666666666666" ||
                cnpj == "77777777777777" ||
                cnpj == "88888888888888" ||
                cnpj == "99999999999999")
                return false;

            // Valida DVs
            tamanho = cnpj.length - 2
            numeros = cnpj.substring(0, tamanho);
            digitos = cnpj.substring(tamanho);
            soma = 0;
            pos = tamanho - 7;
            for (i = tamanho; i >= 1; i--) {
                soma += numeros.charAt(tamanho - i) * pos--;
                if (pos < 2)
                    pos = 9;
            }
            resultado = soma % 11 < 2 ? 0 : 11 - soma % 11;
            if (resultado != digitos.charAt(0))
                return false;

            tamanho = tamanho + 1;
            numeros = cnpj.substring(0, tamanho);
            soma = 0;
            pos = tamanho - 7;
            for (i = tamanho; i >= 1; i--) {
                soma += numeros.charAt(tamanho - i) * pos--;
                if (pos < 2)
                    pos = 9;
            }
            resultado = soma % 11 < 2 ? 0 : 11 - soma % 11;
            if (resultado != digitos.charAt(1))
                return false;

            return true;

        }
       
        function validarCPF(cpf) {
            cpf = cpf.replace(/[^\d]+/g, '');
            if (cpf == '') return false;
            // Elimina CPFs invalidos conhecidos	
            if (cpf.length != 11 ||
                cpf == "00000000000" ||
                cpf == "11111111111" ||
                cpf == "22222222222" ||
                cpf == "33333333333" ||
                cpf == "44444444444" ||
                cpf == "55555555555" ||
                cpf == "66666666666" ||
                cpf == "77777777777" ||
                cpf == "88888888888" ||
                cpf == "99999999999")
                return false;
            // Valida 1o digito	
            add = 0;
            for (i = 0; i < 9; i++)
                add += parseInt(cpf.charAt(i)) * (10 - i);
            rev = 11 - (add % 11);
            if (rev == 10 || rev == 11)
                rev = 0;
            if (rev != parseInt(cpf.charAt(9)))
                return false;
            // Valida 2o digito	
            add = 0;
            for (i = 0; i < 10; i++)
                add += parseInt(cpf.charAt(i)) * (11 - i);
            rev = 11 - (add % 11);
            if (rev == 10 || rev == 11)
                rev = 0;
            if (rev != parseInt(cpf.charAt(10)))
                return false;
            return true;
        }


        //checa se o campo tem somente numeros
        let checkNumber = function() {
            let value = $(this).val();
            if (value.trim() !== "") {
                var regra = /^[0-9]+$/;
                if (!value.match(regra)) {
                    swal("Permitido somente números");
                    $(this).val('');
                }
            }
        };

        $('#cc-conta').blur(checkNumber);
        $('#agencia-conta').blur(checkNumber);
        $('#banco-conta').blur(checkNumber);
    </script>

    <script>
        //Realizando a transitação das etapas de cadastro do parceiro
        $(document).ready(function() {
            <?php if ($this->request->getCookie('direct_link') == '1') echo "$('#etapa1').hide();\n"; echo "$('#etapa3').show();\n"; ?>
            $('#button-et1').click(function() {
                let radio = $("input[name='role']:checked");
                if (radio.length == 0) {
                    swal('Selecione uma opção');
                    return;
                }
                var radioValue = $("input[name='role']:checked").val();
                $('#etapa1').hide();
                if (radioValue == "2") {                    
                    $('#etapa3').show();
                } else {                    
                    $('#etapa2').show();
                }                
            });

        });
        $(document).on('click', '#button-et2', function() {
            var radioValue = $("input[name='tpdoc']:checked").val();
            if (radioValue == "2") {
                $('#form-cpf').hide();
                $('#form-cnpj').show();
            }
            $('#etapa2').hide();
            $('#etapa3').show();

        });
        $(document).on('click', '#button-et3', function() {            
            irParaEtapa3();

        });
        //Ou pressionar enter no campo imput
        $('#form-cpf').keypress(function(e) {
            if(e.which == 13) {
                e.preventDefault();
                irParaEtapa3();
            }
        });
        function irParaEtapa3() {
            let cpf = $('#form-cpf').val();
            let cpnj = $('#form-cnpj').val();
            var radioValue = $("input[name='tpdoc']:checked").val();
            let tpUser = $("input[name='role']:checked").val(); //Parceiro ou cliente
            if (radioValue == "2") {
                inscricao = cpnj;
                //form-label
                $('#form-name').attr("placeholder", "Razão social");
                if (!validarCNPJ(inscricao)) {
                    swal('Por favor digite um CNPJ válido antes de prosseguir');
                    return;
                }
            } else {
                inscricao = cpf;
                $('#form-name').attr("placeholder", "Nome");
                if (!validarCPF(inscricao)) {
                    swal('Por favor digite um CPF válido antes de prosseguir');
                    return;
                }
            }
            if (inscricao < 1) {
                swal('Por favor digite o CPF/CNPJ antes de prosseguir');
                return;

            } else {
                $.get(`/users/check-cpf/${tpUser}/${inscricao}`, function(data) {
                    let exist = data.exists;
                    if (exist) {
                        swal("Cadastrado já existe !", "Clique em OK para ser redirecionado à tela de recuperação de senha")
                            .then((value) => {
                                window.location.href = "/users/recover-password";
                                
                            });
                    } else {
                        $('#etapa3').hide();
                        $('#etapa4').show();
                    }
                });

            }
        }
        $(document).on('click', '#button-et4', function() {
            irParaEtapa4();
        }); //Ou pressionar enter no campo input

        $('#form-password-confirm').keypress(function(e) {
            if(e.which == 13) {
                e.preventDefault();
                irParaEtapa4();
            }
        });

        function irParaEtapa4(){
            let nome = $('#form-name').val();
            let email = $('#form-email').val();
            let psw = $('#form-password').val();
            let pswc = $('#form-password-confirm').val();
            
            if (psw !== pswc) {
                swal('Senha e confirmação de senha não conferem');
                return;
            }
            if ((nome.length < 1) || (email.length < 1) || (psw.length < 1) || (pswc.length < 1)) {
                swal('Todos os campos são obrigatórios');
                return;
            } else {
                $.get(`/users/check-email/${email}`, function(data) {

                    let exist = data.exists;
                    if (exist) {
                        swal("E-mail já cadastrado", "Clique em OK para ser redirecionado à tela de recuperação de senha")
                            .then((value) => {
                                window.location.href = "/users/recover-password";
                            });
                    } else {
                        let radio = $("input[name='role']:checked");
                        if (radio.val() == 2) {
                            $('#etapa4').hide();
                            $('#etapa5').show();

                        } else {
                            $('#etapa4').hide();
                            $('#etapa7').show();
                            /*$("#form-register").submit();
                            $.ajax({
                            async:true,
                            data: $("#conteudo").serialize(),
                            dataType:"html",
                            beforeSend: function (xhr) { // Add this line
                                xhr.setRequestHeader('X-CSRF-Token', $('[name="_csrfToken"]').val());
                            },  // Add this line
                            success:function (data, textStatus) {
                                console.log( data);
                            },
                            type:"post", url:"<?= $this->Url->build(['action' => 'register']) ?>"});
                            return false;*/
                                 
                        }
                    }
                });
            }
        }

        $(document).on('click', '#buttom-et5', function() {
            let ingles = $('#ingles-nivel').val();
            let exp = $('#exp-compras').val();
            let cat = $('#cat-atuacao').val();
            $("#acting_cat").val(cat);

            if ((ingles.length < 1) || (exp.length < 1) || cat === null || (cat.length < 1)) {
                swal('Todos os campos são obrigatórios');
                return;
            }
            let radio = $("input[name='role']:checked");
            if (radio.val() == 2) {
                //$("#form-register").submit(); // encerrando o cadastro do parceiro por aqui, por solicitação do cliente
                $('#etapa5').hide();
                $('#etapa7').show();
            }
            //$('#etapa5').hide();
            //$('#etapa6').show();
        });
        $(document).on('click', '#submit-btn5', function() {
            let nome = $('#nome-conta').val();
            let cpf = $('#cpf-conta').val();
            let banco = $('#banco-conta').val();
            let agencia = $('#agencia-conta').val();
            let conta = $('#cc-conta').val();
            if ((nome.length < 1) || (cpf.length < 1) || (banco.length < 1) || (agencia.length < 1) || (conta.length < 1)) {
                swal('Todos os campos são obrigatórios');
                return;
            }
            $("#form-register").submit();
        });

        //TERMO DE USO
        $(document).on('click', '#button-et7', function() {
            let checked = $('#check-termos-uso').is(':checked');
            if(checked){
                $('#terms_of_use').val(1)
                $("#form-register").submit();
            }else{
                swal('Você precisa aceitar nossos termos de uso para concluir.', {
                    icon: 'warning',
                    title: 'Aviso'
                });
            }
        }); //Ou pressionar enter no campo input
    </script>
</body>

</html>
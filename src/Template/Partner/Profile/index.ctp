<?php $this->assign('title', 'Meu perfil'); ?>

<?= $this->Form->create($user, ['type' => 'file']) ?>

<div id="container" class="container-fluid container-edit-partner">
    <div class="row">
        <div class="titulo-perfil">Dados</div>
    </div>
</div>

<div id="main" class="container-fluid edit-partner-title">
    <div class="box-dropify col-1">
        <input type="file" class="dropify" id="upload-profile" name="nova_imagem" data-default-file="<?= $user->getProfileUrl() ?>" data-height="160" data-max-file-size="5m" data-min-width="160" data-max-width="800" data-min-height="160" data-max-height="800" data-show-loader="false" data-allowed-formats="portrait square" allowedFileExtensions="png jpg jpeg" data-errors-position="outside" alt="Sua foto de perfil" />
        <!--O input abaixo serve para informar se o parceiro removeu ou não sua imagem de perfil-->
        <input type="number" id="removedImageProfile" name="removedImageProfile" style="display:none" value="0">
    </div>
    <div class="col-1 separador"></div>
    <div class='col-md-3 dados-bancarios'>
        <div class="title-dadosbancarios" id="title-dadosPartner">Dados Bancários</div>
        <div>
            <button type="button" id="ver-dadosbancarios" class="col-xs-9 btn ver-dadosbancarios">Ver</button>
        </div>
    </div>
</div>

<div class="container-fluid container-partner-form">

    <!-- Form dos dados bancários -->
    <div id="dados-bancarios" style="display: none;">
        <div class="form-row">
            <div class="form-group col-md-6">
                <label>Titular da conta</label>
                <input type="text" class="form-control" id="user-bank_username" name="bank_username" onkeyup="maiuscula(this)" value="<?= $user->bank_username ?>">
            </div>
            <div class="form-group col-md-6">
                <label>CPF</label>
                <input type='text' class='form-control' id="user-bank_cpf" name="bank_cpf" value="<?= $user->bank_cpf; ?>" />
            </div>
        </div>
        <div class="form-row">
            <div class="form-group col-md-4">
                <label>Banco</label>
                <input type="text" class="form-control" id="user-bank_name" name="bank_name" value="<?= $user->bank_name ?>">
            </div>
            <div class="form-group col-md-3">
                <label>Agência</label>
                <input type='text' class='form-control' id="user-bank_agency" name="bank_agency" value="<?= $user->bank_agency ?>" />
            </div>
            <div class="form-group col-md-3">
                <label>Conta</label>
                <input type='text' class='form-control' id="user-bank_account" name="bank_account" value="<?= $user->bank_account ?>" />
            </div>

        </div>

    </div>

    <!-- Form editar perfil -->
    <div class="row partner-form" id="partner-form">
        <div class="form-row">
            <div class="form-group col-md-6">
                <label for="name">Nome completo</label>
                <input type="text" class="form-control" id="user-name" name="name" value="<?= $user->name ?>" readonly="readonly">
            </div>
            <div class="form-group col-md-3">
                <label>CPF</label>
                <input type='text' class='form-control' id="user-cpf" name="doc_cpf" value="<?= $user->doc_cpf ?>" readonly="readonly" />
            </div>
            <div class="form-group col-md-3">
                <label>RG</label>
                <input type='text' class='form-control' id="doc_rg" name="doc_rg" value="<?= $user->doc_rg ?>" />
            </div>
        </div>

        <div class="form-row">
            <div class="form-group col-md-3">
                <label>Telefone</label>
                <input type="text" class="form-control phone_with_ddd" id="user-telephone" name="telephone" value="<?= $user->telephone ?>">
            </div>
            <div class="form-group col-md-3">
                <label>Celular</label>
                <input type='text' class='form-control cellphone_with_ddd' id="user-cellphone" name="cellphone" pattern=".{14,}" required title="Campo celular incompleto" value="<?= $user->cellphone ?>" />
            </div>
            <div class="form-group col-md-6">
                <label>Email</label>
                <input type='text' class='form-control' id="user-email" name="email" value="<?= $user->email ?>" />
            </div>
        </div>
        <div class="form-row">
            <div class="form-group col-md-4">
                <label>Nível de inglês</label>
                <select class="form-control" id="ingles-nivel" name="english_level">
                    <option value="0" <?= $user->english_level == 0 ? 'selected' : '' ?>>Básico</option>
                    <option value="1" <?= $user->english_level == 1 ? 'selected' : '' ?>>Intermediário</option>
                    <option value="2" <?= $user->english_level == 2 ? 'selected' : '' ?>>Fluente</option>
                </select>
            </div>
            <div class="form-group col-md-4">
                <label>Anos de experiência em Compras</label>
                <select class="form-control" id="exp-compras" name="purchasing_exp">
                    <option value="0" <?= $user->english_level == 0 ? 'selected' : '' ?>>Não tenho experiência</option>
                    <?php
                    for ($i = 1; $i <= 100; $i++) :
                        $selected = $user->purchasing_exp == $i ? 'selected' : '';
                        echo "<option value=\"{$i}\" {$selected}>{$i}</option>";
                    endfor; ?>
                </select>
            </div>
            <div class="modal fade" id="modal-categoria" tabindex="-1" role="dialog">
                <div id="modal-dialog-perfil-categoria" class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                            <h3 class="modal-title">Escolha as categorias que deseja</h3>
                        </div>
                        <div class="modal-body">
                        <div id="result-categoria"></div>
                            <div class="row">
                                <div class="item-categoria col-md-6">
                                    <input class="checkbox" type="checkbox" name="check00" id="check00" <?php for($i = 0; $i < count($categoryPartnerSeparada); $i++){echo $categoryPartnerSeparada[$i] == 0 ? 'checked' : '';}?>>
                                    <label for="check00">Todas as categorias</label>
                                </div>
                                <div class="item-categoria col-md-6">
                                    <input class="checkbox" type="checkbox" name="check29" id="check29" <?php for($i = 0; $i < count($categoryPartnerSeparada); $i++){echo $categoryPartnerSeparada[$i] == 29 ? 'checked' : '';}?>>
                                    <label for="check29">Nenhuma das Listadas</label>
                                </div>
                            </div>
                            <div class="row">
                                <div class="item-categoria col-md-6">
                                    <input class="checkbox" type="checkbox" name="check01" id="check01" <?php for($i = 0; $i < count($categoryPartnerSeparada); $i++){echo $categoryPartnerSeparada[$i] == 1 ? 'checked' : '';}?>>
                                    <label for="check01">Apps e Jogos</label>
                                </div>
                                <div class="item-categoria col-md-6">
                                    <input class="checkbox" type="checkbox" name="check02" id="check02" <?php for($i = 0; $i < count($categoryPartnerSeparada); $i++){echo $categoryPartnerSeparada[$i] == 2 ? 'checked' : '';}?>>
                                    <label for="check02">Bebês</label>
                                </div>
                            </div>
                            <div class="row">
                                <div class="item-categoria col-md-6">
                                    <input class="checkbox" type="checkbox" name="check03" id="check03" <?php for($i = 0; $i < count($categoryPartnerSeparada); $i++){echo $categoryPartnerSeparada[$i] == 3 ? 'checked' : '';}?>>
                                    <label for="check03">Beleza</label>
                                </div>
                                <div class="item-categoria col-md-6">
                                    <input class="checkbox" type="checkbox" name="check04" id="check04" <?php for($i = 0; $i < count($categoryPartnerSeparada); $i++){echo $categoryPartnerSeparada[$i] == 4 ? 'checked' : '';}?>>
                                    <label for="check04">Bolsas, Malas e Mochila</label>
                                </div>
                            </div>
                            <div class="row">
                                <div class="item-categoria col-md-6">
                                    <input class="checkbox" type="checkbox" name="check05" id="check05" <?php for($i = 0; $i < count($categoryPartnerSeparada); $i++){echo $categoryPartnerSeparada[$i] == 5 ? 'checked' : '';}?>>
                                    <label for="check05">Brinquedos e Jogos</label>
                                </div>
                                <div class="item-categoria col-md-6">
                                    <input class="checkbox" type="checkbox" name="check06" id="check06" <?php for($i = 0; $i < count($categoryPartnerSeparada); $i++){echo $categoryPartnerSeparada[$i] == 6 ? 'checked' : '';}?>>
                                    <label for="check06">Casa</label>
                                </div>
                            </div>
                            <div class="row">
                                <div class="item-categoria col-md-6">
                                    <input class="checkbox" type="checkbox" name="check07" id="check07" <?php for($i = 0; $i < count($categoryPartnerSeparada); $i++){echo $categoryPartnerSeparada[$i] == 7 ? 'checked' : '';}?>>
                                    <label for="check07">CD e Vinil</label>
                                </div>
                                <div class="item-categoria col-md-6">
                                    <input class="checkbox" type="checkbox" name="check08" id="check08" <?php for($i = 0; $i < count($categoryPartnerSeparada); $i++){echo $categoryPartnerSeparada[$i] == 8 ? 'checked' : '';}?>>
                                    <label for="check08">Computadores e Informática</label>
                                </div>
                            </div>
                            <div class="row">
                                <div class="item-categoria col-md-6">
                                    <input class="checkbox" type="checkbox" name="check09" id="check09" <?php for($i = 0; $i < count($categoryPartnerSeparada); $i++){echo $categoryPartnerSeparada[$i] == 9 ? 'checked' : '';}?>>
                                    <label for="check09">Cozinha</label>
                                </div>
                                <div class="item-categoria col-md-6">
                                    <input class="checkbox" type="checkbox" name="check10" id="check10" <?php for($i = 0; $i < count($categoryPartnerSeparada); $i++){echo $categoryPartnerSeparada[$i] == 10 ? 'checked' : '';}?>>
                                    <label for="check10">DVD e Blu-Ray</label>
                                </div>
                            </div>
                            <div class="row">
                                <div class="item-categoria col-md-6">
                                    <input class="checkbox" type="checkbox" name="check11" id="check11" <?php for($i = 0; $i < count($categoryPartnerSeparada); $i++){echo $categoryPartnerSeparada[$i] == 11 ? 'checked' : '';}?>>
                                    <label for="check11">Eletrodomésticos</label>
                                </div>
                                <div class="item-categoria col-md-6">
                                    <input class="checkbox" type="checkbox" name="check12" id="check12" <?php for($i = 0; $i < count($categoryPartnerSeparada); $i++){echo $categoryPartnerSeparada[$i] == 12 ? 'checked' : '';}?>>
                                    <label for="check12">Eletrônicos</label>
                                </div>
                            </div>
                            <div class="row">
                                <div class="item-categoria col-md-6">
                                    <input class="checkbox" type="checkbox" name="check13" id="check13" <?php for($i = 0; $i < count($categoryPartnerSeparada); $i++){echo $categoryPartnerSeparada[$i] == 13 ? 'checked' : '';}?>>
                                    <label for="check13">Esporte e Aventura</label>
                                </div>
                                <div class="item-categoria col-md-6">
                                    <input class="checkbox" type="checkbox" name="check14" id="check14" <?php for($i = 0; $i < count($categoryPartnerSeparada); $i++){echo $categoryPartnerSeparada[$i] == 14 ? 'checked' : '';}?>>
                                    <label for="check14">Ferramentas e Materiais de Construção</label>
                                </div>
                            </div>
                            <div class="row">
                                <div class="item-categoria col-md-6">
                                    <input class="checkbox" type="checkbox" name="check15" id="check15" <?php for($i = 0; $i < count($categoryPartnerSeparada); $i++){echo $categoryPartnerSeparada[$i] == 15 ? 'checked' : '';}?> >
                                    <label for="check15">Games</label>
                                </div>
                                <div class="item-categoria col-md-6">
                                    <input class="checkbox" type="checkbox" name="check16" id="check16" <?php for($i = 0; $i < count($categoryPartnerSeparada); $i++){echo $categoryPartnerSeparada[$i] == 16 ? 'checked' : '';}?>>
                                    <label for="check16">Jardim e Piscina</label>
                                </div>
                            </div>
                            <div class="row">
                                <div class="item-categoria col-md-6">
                                    <input class="checkbox" type="checkbox" name="check17" id="check17" <?php for($i = 0; $i < count($categoryPartnerSeparada); $i++){echo $categoryPartnerSeparada[$i] == 17 ? 'checked' : '';}?>>
                                    <label for="check17">Livros</label>
                                </div>
                                <div class="item-categoria col-md-6">
                                    <input class="checkbox" type="checkbox" name="check18" id="check18" <?php for($i = 0; $i < count($categoryPartnerSeparada); $i++){echo $categoryPartnerSeparada[$i] == 18 ? 'checked' : '';}?>>
                                    <label for="check18">Material de Escritório e Papelaria</label>
                                </div>
                            </div>
                            <div class="row">
                                <div class="item-categoria col-md-6">
                                    <input class="checkbox" type="checkbox" name="check19" id="check19" <?php for($i = 0; $i < count($categoryPartnerSeparada); $i++){echo $categoryPartnerSeparada[$i] == 19 ? 'checked' : '';}?>>
                                    <label for="check19">Móveis e Decoração</label>
                                </div>
                                <div class="item-categoria col-md-6">
                                    <input class="checkbox" type="checkbox" name="check20" id="check20" <?php for($i = 0; $i < count($categoryPartnerSeparada); $i++){echo $categoryPartnerSeparada[$i] == 20 ? 'checked' : '';}?>>
                                    <label for="check20">Roupas, Calçados e Joias</label>
                                </div>
                            </div>
                            <div class="row">
                                <div class="item-categoria col-md-6">
                                    <input class="checkbox" type="checkbox" name="check21" id="check21" <?php for($i = 0; $i < count($categoryPartnerSeparada); $i++){echo $categoryPartnerSeparada[$i] == 21 ? 'checked' : '';}?>>
                                    <label for="check21">Saúde e Cuidados Pessoais</label>
                                </div>
                                <div class="item-categoria col-md-6">
                                    <input class="checkbox" type="checkbox" name="check22" id="check22" <?php for($i = 0; $i < count($categoryPartnerSeparada); $i++){echo $categoryPartnerSeparada[$i] == 22 ? 'checked' : '';}?>>
                                    <label for="check22">Lista Compras Supermercado</label>
                                </div>
                            </div>
                            <div class="row">
                                <div class="item-categoria col-md-6">
                                    <input class="checkbox" type="checkbox" name="check23" id="check23" <?php for($i = 0; $i < count($categoryPartnerSeparada); $i++){echo $categoryPartnerSeparada[$i] == 23 ? 'checked' : '';}?>>
                                    <label for="check23">Médico Hospitalar</label>
                                </div>
                                <div class="item-categoria col-md-6">
                                    <input class="checkbox" type="checkbox" name="check24" id="check24" <?php for($i = 0; $i < count($categoryPartnerSeparada); $i++){echo $categoryPartnerSeparada[$i] == 24 ? 'checked' : '';}?>>
                                    <label for="check24">Turismo</label>
                                </div>
                            </div>
                            <div class="row">
                                <div class="item-categoria col-md-6">
                                    <input class="checkbox" type="checkbox" name="check25" id="check25" <?php for($i = 0; $i < count($categoryPartnerSeparada); $i++){echo $categoryPartnerSeparada[$i] == 25 ? 'checked' : '';}?>>
                                    <label for="check25">Químico</label>
                                </div>
                                <div class="item-categoria col-md-6">
                                    <input class="checkbox" type="checkbox" name="check26" id="check26" <?php for($i = 0; $i < count($categoryPartnerSeparada); $i++){echo $categoryPartnerSeparada[$i] == 26 ? 'checked' : '';}?>>
                                    <label for="check26">MRO</label>
                                </div>
                            </div>
                            <div class="row">
                                <div class="item-categoria col-md-6">
                                    <input class="checkbox" type="checkbox" name="check27" id="check27" <?php for($i = 0; $i < count($categoryPartnerSeparada); $i++){echo $categoryPartnerSeparada[$i] == 27 ? 'checked' : '';}?>>
                                    <label for="check27">Clube de Assinaturas</label>
                                </div>
                                <div class="item-categoria col-md-6">
                                    <input class="checkbox" type="checkbox" name="check28" id="check28" <?php for($i = 0; $i < count($categoryPartnerSeparada); $i++){echo $categoryPartnerSeparada[$i] == 28 ? 'checked' : '';}?>>
                                    <label for="check28">Cursos Online</label>
                                </div>
                            </div>
                            <div class="row">
                                <div class="item-categoria col-md-6">
                                    <input class="checkbox" type="checkbox" name="check30" id="check30" <?php for($i = 0; $i < count($categoryPartnerSeparada); $i++){echo $categoryPartnerSeparada[$i] == 30 ? 'checked' : '';}?>>
                                    <label for="check30">Material de limpeza</label>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button id="btn-fechar-categorias" type="button" class="btn btn-primary" onclick="receberNovasCategorias()">Fechar</button>
                        </div>
                    </div><!-- /.modal-content -->
                </div><!-- /.modal-dialog -->
            </div><!-- /.modal -->
            <div class="form-group col-md-4">
                <label>Categoria de atuação</label>
                <br>
                <div data-toggle="modal" data-target="#modal-categoria">
                    <input type="button" style="width:100%;background-color: #004268;color:#fff" class="btn" value="Visualizar">
                    <input type="hidden" id="acting_cat" name="acting_cat" value="">
                </div>
            </div>
            <script>
                var novasCategoriasArr = [];

                //Se todas as categoria estiver maracada
                //e desmarcar uma, desmarca também a "Todas as categorias"
                $('.checkbox').click(function(){
                    if($('#check00').prop('checked')){
                        if(!$(this).prop("checked")){
                            $('#check00').prop('checked',false);
                        }
                    }
                });
                
                function todosCamposDesmarcados(){
                    return !$('#check01').prop('checked') &&
                    !$('#check02').prop('checked') &&
                    !$('#check03').prop('checked') &&
                    !$('#check04').prop('checked') &&
                    !$('#check05').prop('checked') &&
                    !$('#check06').prop('checked') &&
                    !$('#check07').prop('checked') &&
                    !$('#check08').prop('checked') &&
                    !$('#check09').prop('checked') &&
                    !$('#check10').prop('checked') &&
                    !$('#check11').prop('checked') &&
                    !$('#check12').prop('checked') &&
                    !$('#check13').prop('checked') &&
                    !$('#check14').prop('checked') &&
                    !$('#check15').prop('checked') &&
                    !$('#check16').prop('checked') &&
                    !$('#check17').prop('checked') &&
                    !$('#check18').prop('checked') &&
                    !$('#check19').prop('checked') &&
                    !$('#check20').prop('checked') &&
                    !$('#check21').prop('checked') &&
                    !$('#check22').prop('checked') &&
                    !$('#check23').prop('checked') &&
                    !$('#check24').prop('checked') &&
                    !$('#check25').prop('checked') &&
                    !$('#check26').prop('checked') &&
                    !$('#check27').prop('checked') &&
                    !$('#check28').prop('checked') &&
                    !$('#check29').prop('checked') &&
                    !$('#check30').prop('checked') ? true : false;
                }

                function desabilitarCategorias(status){
                    $('#check00').prop('disabled', status);
                    $('#check01').prop('disabled', status);
                    $('#check02').prop('disabled', status);
                    $('#check03').prop('disabled', status);
                    $('#check04').prop('disabled', status);
                    $('#check05').prop('disabled', status);
                    $('#check06').prop('disabled', status);
                    $('#check07').prop('disabled', status);
                    $('#check08').prop('disabled', status);
                    $('#check09').prop('disabled', status);
                    $('#check10').prop('disabled', status);
                    $('#check11').prop('disabled', status);
                    $('#check12').prop('disabled', status);
                    $('#check13').prop('disabled', status);
                    $('#check14').prop('disabled', status);
                    $('#check15').prop('disabled', status);
                    $('#check16').prop('disabled', status);
                    $('#check17').prop('disabled', status);
                    $('#check18').prop('disabled', status);
                    $('#check19').prop('disabled', status);
                    $('#check20').prop('disabled', status);
                    $('#check21').prop('disabled', status);
                    $('#check22').prop('disabled', status);
                    $('#check23').prop('disabled', status);
                    $('#check24').prop('disabled', status);
                    $('#check25').prop('disabled', status);
                    $('#check26').prop('disabled', status);
                    $('#check27').prop('disabled', status);
                    $('#check28').prop('disabled', status);
                    $('#check30').prop('disabled', status);
                }
                function marcarTodasCategorias(id){
                    $('#check01').prop('checked', $(id).prop("checked"));
                    $('#check02').prop('checked', $(id).prop("checked"));
                    $('#check03').prop('checked', $(id).prop("checked"));
                    $('#check04').prop('checked', $(id).prop("checked"));
                    $('#check05').prop('checked', $(id).prop("checked"));
                    $('#check06').prop('checked', $(id).prop("checked"));
                    $('#check07').prop('checked', $(id).prop("checked"));
                    $('#check08').prop('checked', $(id).prop("checked"));
                    $('#check09').prop('checked', $(id).prop("checked"));
                    $('#check10').prop('checked', $(id).prop("checked"));
                    $('#check11').prop('checked', $(id).prop("checked"));
                    $('#check12').prop('checked', $(id).prop("checked"));
                    $('#check13').prop('checked', $(id).prop("checked"));
                    $('#check14').prop('checked', $(id).prop("checked"));
                    $('#check15').prop('checked', $(id).prop("checked"));
                    $('#check16').prop('checked', $(id).prop("checked"));
                    $('#check17').prop('checked', $(id).prop("checked"));
                    $('#check18').prop('checked', $(id).prop("checked"));
                    $('#check19').prop('checked', $(id).prop("checked"));
                    $('#check20').prop('checked', $(id).prop("checked"));
                    $('#check21').prop('checked', $(id).prop("checked"));
                    $('#check22').prop('checked', $(id).prop("checked"));
                    $('#check23').prop('checked', $(id).prop("checked"));
                    $('#check24').prop('checked', $(id).prop("checked"));
                    $('#check25').prop('checked', $(id).prop("checked"));
                    $('#check26').prop('checked', $(id).prop("checked"));
                    $('#check27').prop('checked', $(id).prop("checked"));
                    $('#check28').prop('checked', $(id).prop("checked"));
                    $('#check30').prop('checked', $(id).prop("checked"));
                    if($(id).prop('checked')){
                        $('#check29').prop('disabled', true);
                    }else{
                        $('#check29').prop('disabled', false);
                    }
                }
                //Desabilitando de inicio os outros campos caso a categoria seja 29
                if($("#check29").prop('checked')){
                    desabilitarCategorias(true);
                }

                if($("#check00").prop('checked')){
                    marcarTodasCategorias('#check00');
                }

                //VALIDAÇÃO DA CATEGORIA
                $("#check00").change(function () {
                    marcarTodasCategorias('#check00');
                });
                $("#check29").change(function () {
                    if($("#check29").prop('checked')){
                        desabilitarCategorias(true);
                    }else{
                        desabilitarCategorias(false);
                    }
                });
                function receberNovasCategorias(){
                    if($("#check00").prop('checked')){
                        novasCategoriasArr[0] = 0;
                    }else if($("#check29").prop('checked')){
                        novasCategoriasArr[0] = 29;
                    }else{

                        var array = [ 
                                        "#check00",
                                        "#check01",
                                        "#check02",
                                        "#check03",
                                        "#check04",
                                        "#check05",
                                        "#check06",
                                        "#check07",
                                        "#check08",
                                        "#check09",
                                        "#check10",
                                        "#check11",
                                        "#check12",
                                        "#check13",
                                        "#check14",
                                        "#check15",
                                        "#check16",
                                        "#check17",
                                        "#check18",
                                        "#check19",
                                        "#check20",
                                        "#check21",
                                        "#check22",
                                        "#check23",
                                        "#check24",
                                        "#check25",
                                        "#check26",
                                        "#check27",
                                        "#check28",
                                        "#check29",
                                        "#check30"
                                    ];
                        var j = 0;
                        for(var i = 1; i < array.length; i++){
                            if($(array[i]).prop('checked')){
                                novasCategoriasArr[j] = i;
                                j++;
                            }
                        }
                    }
                    if(todosCamposDesmarcados()){
                        $("#result-categoria").append('<div style="width:100%;text-align:center;background-color:rgb(124, 209, 249)!important;border:none" class="alert alert-info" role="alert">Por favor, escolha ao menos uma categoria</div>');
                    }else{
                        $("#result-categoria").remove();
                        $("#acting_cat").val(novasCategoriasArr);
                        $("#btn-fechar-categorias").attr('data-dismiss','modal');
                    }
                }
            </script>
        </div>
        <div class="form-row">
            <div class="form-group col-md-6">
                <label>Cep</label>
                <input type="text" class="form-control cep" id="user-address_zipcode" name="address_zipcode" value="<?= $user->address_zipcode ?>">
            </div>
            <div class="form-group col-md-3">
                <label>Rua</label>
                <input type='text' class='form-control' id="user-address_street" name="address_street" value="<?= $user->address_street ?>" />
            </div>
            <div class="form-group col-md-3">
                <label>Número</label>
                <input type='text' class='form-control' id="user-address_number" name="address_number" value="<?= $user->address_number ?>" />
            </div>
        </div>
        <div class="form-row">
            <div class="form-group col-md-6">
                <label>Complemento</label>
                <input type="text" class="form-control" id="user-address_complement" name="address_complement" value="<?= $user->address_complement ?>">
            </div>
            <div class="form-group col-md-3">
                <label>Cidade</label>
                <input type='text' class='form-control' id="user-address_city" name="address_city" value="<?= $user->address_city ?>" />
            </div>
            <div class="form-group col-md-3">
                <label>UF</label>
                <input type='text' class='form-control' id="user-address_uf" name="address_uf" value="<?= $user->address_uf ?>" />
            </div>
        </div>

        <div class="form-row">
            <div class="form-group col-md-6">
                <label>Bairro</label>
                <input type="text" class="form-control" id="user-address_neighborhood" name="address_neighborhood" value="<?= $user->address_neighborhood ?>">
            </div>
            <div class="form-group col-md-6">
                <label>Data de Nascimento</label>
                <input type='text' class='form-control date' id="user-birth_date" name="birth_date" value="<?= $user->birth_date ? $user->birth_date->format('d/m/Y') : ''; ?>" />
            </div>
        </div>

        <div class="form-row">
            <div class="form-group col-md-6">
                <label>Senha</label>
                <input type="password" class="form-control" id="user-password" name="password" placeholder="Cadastrar nova senha">
            </div>
            <div class="form-group col-md-6">
                <label>Confirmar Senha</label>
                <input type='password' class='form-control' id="user-confirm-password" name="confirm-password" />
            </div>
        </div>
        <div class="form-row" style="display: none;" id="password-rule-info">
            <span style="font-size: 13px; color: #ff0000;    margin-left: 20px;">A senha deve conter no mínimo 6 caracteres: sendo ao menos 1 letra maiúscula, 1 letra minúscula e 1 símbolo</span>
        </div>
    </div>
    <div class="form-row">
        <button type="submit" class="btn btn-success col-md-2 btn-edit2">Salvar</button>
    </div>
</div>

<!-- jQuery 3 -->
<script src="<?= $this->Url->build('bower_components/jquery/dist/jquery.min.js') ?>"></script>
<!-- jQuery Mask Plugin -->
<script src="<?= $this->Url->build('bower_components/jQuery-Mask-Plugin-master/dist/jquery.mask.min.js') ?>"></script>
<!-- Sweetalert -->
<script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>

<!--Dropify-->
<script src="<?= $this->Url->build('plugins/dropify/dist/js/dropify.js') ?>"></script>
<script>
    //Iniciando plugin dropify
    $('.dropify').dropify({
        messages: {
            'default': 'Arraste e solte um arquivo ou clique',
            'replace': 'Arraste e solte ou clique para substituir',
            'remove': 'Remover',
            'error': 'Ops, algo de errado ocorreu.'
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
            wrap: '<div class="dropify-wrapper"></div>',
            loader: '<div class="dropify-loader"></div>',
            message: '<div class="dropify-message"><span class="file-icon" /> <p>{{ default }}</p></div>',
            preview: '<div class="dropify-preview"><span class="dropify-render"></span><div class="dropify-infos"><div class="dropify-infos-inner"><p class="dropify-infos-message">{{ replace }}</p></div></div></div>',
            filename: '',
            clearButton: '<button type="button" class="dropify-clear" style="top: 20%;right: 25.5%;">{{ remove }}</button>',
            errorLine: '<p class="dropify-error">{{ error }}</p>',
            errorsContainer: '<div class="dropify-errors-container"><ul></ul></div>'
        }
    });
</script>

<script>
    $('button[type=button].dropify-clear').click(function() {
        //Removeu e deixou sem imagem
        $('#removedImageProfile').val(1);
    });

    $('#upload-profile').click(function() {
        //Alterou por outra
        $('#removedImageProfile').val(0);
    });
</script>

<script>
    // Alterna entre dados pessoais e dados bancários
    $(document).ready(function() {
        let perfilPartner = true;
        $('#ver-dadosbancarios').click(function() {
            if (perfilPartner) {
                perfilPartner = false;
                $('#partner-form').hide();
                $('#dados-bancarios').show();
                $("#title-dadosPartner").html("Dados Pessoais");
            } else {
                perfilPartner = true;
                $('#partner-form').show();
                $('#dados-bancarios').hide();
                $("#title-dadosPartner").html("Dados Bancários");
            }

        })
    });


    $(document).ready(function() {
        $('.date').mask('00/00/0000');
        $('.cep').mask('00000-000');
        $('.phone_with_ddd').mask('(00) 0000-0000');
        $('.cellphone_with_ddd').mask('(00) 00000-0000');
        $('.placeholder').mask("00/00/0000", {
            placeholder: "__/__/____"
        });

        $('.selectonfocus').mask("00/00/0000", {
            selectOnFocus: true
        });
    });
    // Checa se o e-mail é valido
    $('#user-email').blur(function() {
        let str = $(this).val();
        var re = /^(([^<>()[\]\\.,;:\s@\"]+(\.[^<>()[\]\\.,;:\s@\"]+)*)|(\".+\"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
        if (!re.test(str)) {
            swal("Por favor informe um e-mail válido");
            $(this).val('');
        }
    });


    function maiuscula(z) {
        v = z.value.toUpperCase();
        z.value = v;
    }


    // Checa se a data de nascimento é valida
    $('#user-birth_date').blur(function() {
        let str = $(this).val();
        var regex = /^(?:(?:31(\/|-|\.)(?:0?[13578]|1[02]))\1|(?:(?:29|30)(\/|-|\.)(?:0?[13-9]|1[0-2])\2))(?:(?:1[6-9]|[2-9]\d)?\d{2})$|^(?:29(\/|-|\.)0?2\3(?:(?:(?:1[6-9]|[2-9]\d)?(?:0[48]|[2468][048]|[13579][26])|(?:(?:16|[2468][048]|[3579][26])00))))$|^(?:0?[1-9]|1\d|2[0-8])(\/|-|\.)(?:(?:0?[1-9])|(?:1[0-2]))\4(?:(?:1[6-9]|[2-9]\d)?\d{2})$/;
        if (!regex.test(str)) {
            swal("Por favor informe uma data válida");
            $(this).val('');
        }
    });

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

    $('#user-bank_username').blur(checkLetter);

    const bancoDados = {
        bank_name: "<?= $user->bank_name ?>",
        bank_agency: "<?= $user->bank_agency ?>",
        bank_account: "<?= $user->bank_account ?>",
    };
    //checa se o campo tem somente numeros
    let checkNumber = function() {
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

    // validação do cadastramento de senha: 1 maiusculo 1 minuscula e 1 caractere especial
    $('#user-password').blur(function() {
        let senha = $(this).val();
        let regex = /^(?=^.{6,}$)((?=.*\W+))(?![.\n])(?=.*[A-Z])(?=.*[a-z]).*$/g;

        if (!regex.test(senha)) {
            //alert("A senha deve conter no mínimo 6 caracteres: sendo ao menos 1 letra maiúscula, 1 letra minúscula e 1 símbolo");
            $('#password-rule-info').show();
            this.value = '';
        } else {
            $('#password-rule-info').hide();
        }
    })
    //Digitar o cep e preencher endereço automaticamente
    $("#user-address_zipcode").focusout(function() {
        //Início do Comando AJAX
        $.ajax({
            //O campo URL diz o caminho de onde virá os dados
            //É importante concatenar o valor digitado no CEP
            url: 'https://viacep.com.br/ws/' + $(this).val() + '/json/unicode/',
            //Aqui você deve preencher o tipo de dados que será lido,
            //no caso, estamos lendo JSON.
            dataType: 'json',
            //SUCESS é referente a função que será executada caso
            //ele consiga ler a fonte de dados com sucesso.
            //O parâmetro dentro da função se refere ao nome da variável
            //que você vai dar para ler esse objeto.
            success: function(resposta) {
                //Agora basta definir os valores que você deseja preencher
                //automaticamente nos campos acima.
                console.log(resposta);
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
    jQuery.fn.validacpf = function() {
        this.change(function() {
            CPF = jQuery(this).val();
            if (!CPF) {
                return false;
            }
            erro = new String;
            cpfv = CPF;
            if (cpfv.length == 14 || cpfv.length == 11) {
                cpfv = cpfv.replace('.', '');
                cpfv = cpfv.replace('.', '');
                cpfv = cpfv.replace('-', '');

                var nonNumbers = /\D/;

                if (nonNumbers.test(cpfv)) {
                    erro = "A verificacao de CPF suporta apenas números!";
                } else {
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

                    for (i = 0; i < 11; i++) {
                        a[i] = cpfv.charAt(i);
                        if (i < 9) b += (a[i] * --c);
                    }
                    if ((x = b % 11) < 2) {
                        a[9] = 0
                    } else {
                        a[9] = 11 - x
                    }
                    b = 0;
                    c = 11;
                    for (y = 0; y < 10; y++) b += (a[y] * c--);

                    if ((x = b % 11) < 2) {
                        a[10] = 0;
                    } else {
                        a[10] = 11 - x;
                    }
                    if ((cpfv.charAt(9) != a[9]) || (cpfv.charAt(10) != a[10])) {
                        erro = "Número de CPF inválido.";
                        swal(erro);
                    }
                }
            } else {
                if (cpfv.length == 0) {
                    return false;
                } else {
                    erro = "Número de CPF inválido.";
                    swal(erro);
                }
            }
            if (erro.length > 0) {
                jQuery(this).val('');
                jQuery('.cpf_box').append("<div style='font-size:12px; color:red;'>" + erro + "</div>");
                setTimeout(function() {
                    jQuery(this).focus();
                }, 100);
                return false;
            }
            return jQuery(this);
        });
    }
    jQuery("#user-bank_cpf").validacpf();
</script>

<script>
    $("#cat-15").sttr('select');
</script>

<?= $this->Form->end() ?>
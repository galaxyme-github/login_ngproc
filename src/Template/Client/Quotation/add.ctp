<?php $this->assign('title', 'Adicionar Cotação');?>
    <?=$this->Html->css('https://maxcdn.bootstrapcdn.com/bootstrap/3.4.0/css/bootstrap.min.css');?>
    <div class="dashboard-container">
        <div class="ngproc-card">
            <div class="ngproc-card-title">
                <?=$this->Html->link('< Minhas Cotações', ['action' => 'index'], ['class' => 'btn', 'id' => 'voltar']);?>
            </div>
            <div class="ngproc-card-content">
                <?=$this->Form->create(null, ['id' => 'form-create-cotation', 'type' => 'file', 'enctype' => 'multipart/form-data'])?>

                <!-- Detalhando os input com name duplicado -->
                <input type="hidden" name="provider_qtd" id="provider-qtd">
                <input type="hidden" name="deadline_date" id="deadline_date">
                <input type="hidden" name="coverage" id="coverage">
                <input type="hidden" name="title" id="title">

                <div id="result-cotacao-geral"></div>
                <div class="objetivo-newquot col-md-12">
                    <div class="newquot-title">Seu objetivo é? *</div>
                    <br>
                    <div class="opcoes-newquot">
                        <input type="radio" id="reduzir-cotacao" name="objective" value="1">
                        <label for="reduzir-cotacao">Reduzir custos</label>
                        <br>
                        <input type="radio" id="especifico-cotacao" name="objective" value="2">
                        <label for="especifico-cotacao">Itens de difícil localização</label>
                        <br>
                    </div>
                </div>
                <div class="buscando-newquot col-md-12">
                    <div class="newquot-title">O que você está buscando? *</div>
                    <br>
                    <div class="opcoes-newquot">
                        <div id="radio1">
                            <input type="radio" id="buscando-produto" name="type" value="0">
                            <label for="buscando-produto">Produtos</label>
                        </div>
                        <div id="radio2">
                            <input type="radio" id="buscando-servico" name="type" value="1">
                            <label for="buscando-servico">Serviços</label>
                        </div>
                        <script>
                            $(document).ready(function() {
                                $("#buscando-produto").click(function() {
                                    $("#form-produto").show();
                                    $("#form-servico").hide();
                                });
                            });

                            $(document).ready(function() {
                                $("#buscando-servico").click(function() {
                                    $("#form-servico").show();
                                    $("#form-produto").hide();
                                });
                            });
                        </script>
                        <br>

                    </div>
                    <div class="row" style="margin-bottom:20px">
                        <div class="form-group col-sm-4">
                            <div class="newquot-title" style="margin-bottom:10px"><label for="cep-cotacao">Cadastre um CEP para a cotação *</label></div>
                            <input type="text" class="form-control cep cep-cotacao" id="cep-cotacao" name="address_zipcode" placeholder="Preencha o CEP" required>
                        </div>
                    </div>
                </div>

                <hr class="line-newquote">
                <h5 class="h5 it-obrigatorio">Atenção: Itens com * são obrigatórios</h5>
                <!-- <div class="form-group col-sm-12" style="background-color:#eee; border-radius:5px; margin-bottom:40px">
                    <h5 class="h5 it-obrigatorio">Seu CEP está pendente, para cadastrar uma cotação complete este campo.</h5>
                    <div class="form-group col-sm-3">
                        <input type="text" class="form-control cep" placeholder="Preencha seu CEP" required>
                    </div>
                </div> -->
                <div id="result-cotacao"></div>

                <!-- COTAÇÕES PRODUTOS -->
                    <div class="form-row" id="form-produto" style="display: none">
                        <div class="col-sm-12">
                            <div class="form-group has-feedback col-md-4">
                                <label for="name">Título *</label>
                                <input type="text" class="form-control" id="title_product" name="title_product" placeholder="Título da cotação">
                            </div>
                            <div id="opcao" class="form-group has-feedback col-md-4">
                                <label style="font-size: 13px;" id="opcao">Quantas opções de fornecedor*</label>
                                <input type="number" class="form-control" id="provider_qtd_product" name="provider_qtd_product" placeholder="No máximo 10" min="1" max="10" />
                            </div>

                            <div class="form-group has-feedback col-md-4">
                            <label for="conclusao_prazo">Prazo para conclusão *</label>
                                <input type="text" class="form-control date data-atual" id="deadline_date_product" name="deadline_date_product" placeholder="dd/mm/aaaa" >
                                <label>
                                    <input type="checkbox" id="chkbx-tempo-produto">Tempo indeterminado
                                </label>
                            </div>
                        </div>
                        <div class="col-sm-12">
                            <div class="form-group has-feedback col-sm-5">
                                <label for="abrangencia">Abrangência da cotação *</label>
                                <br>
                                <div >
                                    <label class="radio-inline" style="margin-top: 7px;">
                                        <input class="nacional-checked" type="radio" name="coverage_product" id="coverage_product" value="Nacional"> Nacional
                                    </label>
                                    <label class="radio-inline" style="margin-top: 7px;margin-left: 0px!important">
                                        <input type="radio" name="coverage_product" id="coverage_product" value="Internacional" > Internacional
                                    </label>
                                </div>

                            </div>
                            <div class="col-sm-5">
                                <div id="titulo2" class="form-group has-feedback">

                                    <label for="anexo">Anexar Arquivos Úteis para a Cotação</label>
                                    <div class="box-anexo-new-quotation btn btn-sm form-group has-feedback col-sm-12" style="padding:3px 5px; margin-bottom:20px">
                                    <input type="file" name="anexo[]" id="anexos-file" multiple>
                                    </div>
                                </div>
                            </div>

                        </div> <br><br>
                        <div class="row p-30" >
                            <div id="tabela-cotacoes" class="table-responsive" style="display: flex;border: none;">
                                <table id="cotacoes" class="table table-bordered table-responsive">
                                    <div class="scrollmenu">
                                        <thead>
                                            <tr>
                                                <th>Nome *</th>
                                                <th>Categoria *</th>
                                                <th>Qtd *</th>
                                                <th>Orçamento Unitário *</th>
                                                <th>Fabricante</th>
                                                <th>Modelo</th>
                                                <!-- <th>SKU</th> -->
                                                <th>Ação</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr id="linha-principal">
                                                <td class="lp-s-1"><input class="form-control cotation_item_name0" type="text" name="cotation_product[cotation_product_items][0][product][item_name]" id="cotation_item_name" placeholder="Produto" required></td>
                                                <td class="lp-s-1">
                                                    <select class="form-control cat-cotacao0" style="width:165px;" id="cat-cotacao" name="cotation_product[cotation_product_items][0][product][category_item_prod]" required>
                                                        <option value="" selected disabled>Selecione a categoria</option>
                                                        <!-- <option value="0">Todas as categorias</option> -->
                                                        <option value="29">Nenhuma das Listadas</option>
                                                        <option value="1">Apps e Jogos</option>
                                                        <option value="2">Bebês</option>
                                                        <option value="3">Beleza</option>
                                                        <option value="4">Bolsas, Malas e Mochila</option>
                                                        <option value="5">Brinquedos e Jogos</option>
                                                        <option value="6">Casa</option>
                                                        <option value="7">CD e Vinil</option>
                                                        <option value="27">Clube de Assinaturas</option>
                                                        <option value="8">Computadores e Informática</option>
                                                        <option value="9">Cozinha</option>
                                                        <option value="28">Cursos Online</option>
                                                        <option value="10">DVD e Blu-Ray</option>
                                                        <option value="11">Eletrodomésticos</option>
                                                        <option value="12">Eletrônicos</option>
                                                        <option value="13">Esporte e Aventura</option>
                                                        <option value="14">Ferramentas e Materiais de Construção</option>
                                                        <option value="15">Games</option>
                                                        <option value="16">Jardim e Piscina</option>
                                                        <option value="22">Lista Compras Supermercado</option>
                                                        <option value="17">Livros</option>
                                                        <option value="18">Material de Escritório e Papelaria</option>
                                                        <option value="30">Material de limpeza</option>
                                                        <option value="23">Médico Hospitalar</option>
                                                        <option value="19">Móveis e Decoração</option>
                                                        <option value="26">MRO</option>
                                                        <option value="25">Químico</option>
                                                        <option value="20">Roupas, Calçados e Joias</option>
                                                        <option value="21">Saúde e Cuidados Pessoais</option>
                                                        <option value="24">Turismo</option>
                                                    </select>
                                                </td>
                                                <td class="lp-s-1"><input class="form-control" type="number"  name="cotation_product[cotation_product_items][0][quantity]" id="quantity" min="1" placeholder="Mínimo 1" required></td>
                                                <td class="lp-s-1"><input class="form-control dinheiro-real" type="text" name="cotation_product[cotation_product_items][0][quote]" id="quote" placeholder="R$ 0,00" step="0.01" required></td>
                                                <td class="lp-s-4"><input class="form-control manufacturer0" type="text" name="cotation_product[cotation_product_items][0][product][manufacturer]" id="manufacturer" placeholder="Fabricante"></td>
                                                <td class="lp-s-4"><input class="form-control model0" type="text" name="cotation_product[cotation_product_items][0][product][model]" id="model" placeholder="Modelo"></td>
                                                <!-- <td class="lp-s-4"><input class="form-control" type="text" name="cotation_product[cotation_product_items][0][product][sku]" id=""></td> -->
                                                <td class="lp-s-2" style="text-align: center; padding-top: 15px">---</td>
                                            </tr>
                                        </tbody>
                                    </div>
                                </table>
                            <div id="quadrado" onclick="productsAdd()">
                                <?=$this->Html->image("icons/plus-add.png", ['class' => 'deleterow-btn-add', "width" => "26px", 'heigth' => "26px"])?>
                            </div>
                        </div>
                    <!-- <div id="quadrado" class="quadrado btn" style="background-color:#004268; width:24px; height:24px" onclick="productsAdd()">
                        <div><img src="<?=$this->Url->build('img/icons/add.png')?>" alt="Botão de Adicionar" width="13" height="13" style="margin: -12px 0 0 -6px;"></div>
                    </div> -->

                </div>
                </div>
                    <!-- COTAÇÕES SERVIÇOS -->
                    <div class="form-row" id="form-servico" style="display: none">
                        <div class="col-sm-12">
                            <div class="form-group has-feedback col-sm-6">
                                <label for="name">Título*</label>
                                <input type="text" class="form-control" id="title_service" name="title_service" placeholder="Digite um título">
                            </div>
                            <div class="form-group has-feedback col-sm-6" id="box-cat-servico">
                                <label for="cat-cotacao">Categoria*</label>
                                <select class="form-control" id="cat-cotacao" name="cotation_service[category]">
                                    <option value="" selected disabled>Selecione a categoria</option>
                                    <!-- <option value="0">Todas as categorias</option> -->
                                    <option value="29">Nenhuma das Listadas</option>
                                    <option value="1">Apps e Jogos</option>
                                    <option value="2">Bebês</option>
                                    <option value="3">Beleza</option>
                                    <option value="4">Bolsas, Malas e Mochila</option>
                                    <option value="5">Brinquedos e Jogos</option>
                                    <option value="6">Casa</option>
                                    <option value="7">CD e Vinil</option>
                                    <option value="27">Clube de Assinaturas</option>
                                    <option value="8">Computadores e Informática</option>
                                    <option value="9">Cozinha</option>
                                    <option value="28">Cursos Online</option>
                                    <option value="10">DVD e Blu-Ray</option>
                                    <option value="11">Eletrodomésticos</option>
                                    <option value="12">Eletrônicos</option>
                                    <option value="13">Esporte e Aventura</option>
                                    <option value="14">Ferramentas e Materiais de Construção</option>
                                    <option value="15">Games</option>
                                    <option value="16">Jardim e Piscina</option>
                                    <option value="22">Lista Compras Supermercado</option>
                                    <option value="17">Livros</option>
                                    <option value="18">Material de Escritório e Papelaria</option>
                                    <option value="30">Material de limpeza</option>
                                    <option value="23">Médico Hospitalar</option>
                                    <option value="19">Móveis e Decoração</option>
                                    <option value="26">MRO</option>
                                    <option value="25">Químico</option>
                                    <option value="20">Roupas, Calçados e Joias</option>
                                    <option value="21">Saúde e Cuidados Pessoais</option>
                                    <option value="24">Turismo</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-sm-12">
                            <div class="form-group has-feedback col-sm-6">
                                <label for="descricao-servico">Descrição dos itens para cotação*</label>
                                <input type="text" class="form-control" id="descricao-servico" name="cotation_service[description]" placeholder="Nome dos itens a serem cotados">
                            </div>
                            <div class="form-group has-feedback col-sm-6">
                                <label for="tempoestimado-servico">Tempo estimado de demanda do serviço*</label>
                                <input type="text" class="form-control" id="tempoestimado-servico" name="cotation_service[service_time]" placeholder="Exemplo: 5 dias">
                            </div>
                        </div>
                        <div class="col-sm-12">
                            <div class="form-group has-feedback col-sm-6">
                                <label for="provider_qtd_service">Quantas opções de fornecedor?*</label>
                                <input type="number" class="form-control" id="provider_qtd_service" name="provider_qtd_service" placeholder="No máximo 10">
                            </div>
                            <div class="form-group has-feedback col-sm-6">
                                <label for="tipo_cobranca">Tipo de cobrança*</label>
                                <br>
                                <label class="radio-inline" style="margin-top: 7px;">
                                    <input class="hora-checked" type="radio" name="cotation_service[collection_type]" id="collection_type" value="hora"> Hora
                                </label>
                                <label class="radio-inline" style="margin-top: 7px;">
                                    <input type="radio" name="cotation_service[collection_type]" id="collection_type" value="serviço"> Serviço
                                </label>
                            </div>
                            <br>
                            <br>
                        </div>
                        <div class="col-sm-12">
                            <div class="form-group has-feedback col-sm-6">
                                <label for="expectativa_inicio">Expectativa de início de serviço*</label>
                                <input type="text" class="form-control date date" id="expectativa_inicio" placeholder="dd/mm/aaaa" name="cotation_service[expectation_start]">
                            </div>
                            <div class="form-group has-feedback col-sm-6">
                                <label for="abrangencia">Abrangência da cotação*</label>
                                <br>
                                <label class="radio-inline" style="margin-top: 7px;">
                                    <input id="nacional-serv-checked" type="radio" name="coverage_service" id="coverage_service" value="Nacional"> Nacional
                                </label>
                                <label class="radio-inline" style="margin-top: 7px;">
                                    <input type="radio" name="coverage_service" id="coverage_service" value="Internacional"> Internacional
                                </label>
                            </div>
                        </div>
                        <div class="col-sm-12">
                            <div class="form-group has-feedback col-sm-6">
                                <label for="expectativa_orcamento">Expectativa de orçamento *</label>
                                <input type="text" class="form-control dinheiro-real" id="expectativa_orcamento" name="cotation_service[estimate]" placeholder="R$ 0,00" step="0.01">
                            </div>
                            <div class="form-group has-feedback col-sm-6">
                                <label for="deadline_date_service">Prazo para conclusão*</label>
                                <input type="text" class="form-control date  data-atual" id="deadline_date_service" name="deadline_date_service" placeholder="dd/mm/aaaa">
                                <label>
                                    <input type="checkbox" id="chkbx-tempo-service">Tempo indeterminado
                                </label>
                            </div>
                        </div>
                        <div class="col-sm-12 anexo-servico">
                            <div id="titulo2" class="form-group has-feedback col-sm-6">
                                <label>Anexar fotos ou documentos</label>
                                    <div class="box-anexo-new-quotation btn btn-sm float-left form-group has-feedback">
                                    <input type="file" name="anexo[]" multiple>
                                </div>
                            </div>
                            <br>
                            <br>
                        </div>
                </div>
                <div class= "row"  style="display: flex;    margin-left: 5px;">
                    <div class="col">
                    <?=$this->Html->link(' Voltar', ['action' => 'index'], ['class' => 'btn btn-dark', 'id' => 'preto']);?>
                    </div>
                    <div class="col">
                    <input type="submit" class="btn btn-success" id="verde" style="margin-left:20px;" value="Adicionar">
                    </div>
                </div>
                <?=$this->Form->end();?>

                <!-- fim card content -->
            </div>
        </div>
    </div>

<!-- jQuery 3 -->
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
<!-- <script src="<?=$this->Url->build('bower_components/jquery/dist/jquery.min.js')?>"></script> -->
<!--Ajax-->
<script src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.js"></script>
<!-- <script src="<?=$this->Url->build('bower_components/jquery-ui/jquery-ui.min.js')?>"></script> -->
<!-- jQuery Mask Plugin -->
<script src="<?=$this->Url->build('bower_components/jQuery-Mask-Plugin-master/dist/jquery.mask.min.js')?>"></script>
<!-- Sweetalert -->
<script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
<!-- Bootstrap -->
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.0/js/bootstrap.min.js"></script>
<!-- jquery-maskmoney -->
<script src="<?=$this->Url->build('bower_components/plentz-jquery-maskmoney-cdbeeac/dist/jquery.maskMoney.js')?>"></script>
<!-- Moment.js -->
<script src="<?=$this->Url->build('bower_components/moment/min/moment-with-locales.min.js')?>"></script>
<!-- Sweetalert -->
<script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>

<script>
$('.cep').mask('00000-000');
</script>

<!-- Autocomplete do item da cotação de produtos -->
<!-- <script>
    var productsAll = JSON.parse('<?=json_encode($productsAll)?>');
    var rowsProductsAll = <?=$rowsProductsAll?>;
    var listProductsName = [];
    for(var i = 0; i < rowsProductsAll; i++){
        listProductsName[i] = productsAll[i].item_name;
    }
    function autocomplete(indiceItem){
        $('.cotation_item_name'+indiceItem).autocomplete({
            source: listProductsName
        });
        $('.cotation_item_name'+indiceItem).change(function(){
            var itemNameProduct = $(this).val();
            var found = false;
            var i = 0;
            while(found == false || i < rowsProductsAll){
                if(itemNameProduct == productsAll[i].item_name){
                    $('.cat-cotacao'+indiceItem+' option').removeAttr('selected').filter('[value='+parseInt(productsAll[i].category_item_prod)+']').attr('selected', true);
                    $('.manufacturer'+indiceItem).val(productsAll[i].manufacturer);
                    $('.model'+indiceItem).val(productsAll[i].model);
                    found = true;
                }
                i++;
            }
        });
    }
$(document).ready(function(){
    autocomplete(0);
});
</script> -->

<script>
//DECLARANDO AS FUNÇÕES AQUI
function camposProdutosPreenchidos(){
    if( $("#title_product").val() == ""){
        $("#title_product").focus();
        return false;
    }else if( $("#provider_qtd_product").val() == ""){
        $("#provider_qtd_product").focus();
        return false;
    }else if( $("#deadline_date_product").val() == "" && !$("#chkbx-tempo-produto").prop("checked")){
        $("#deadline_date_product").focus();
        return false;
    }else if( $("#cotation_item_name").val() == ""){
        $("#cotation_item_name").focus();
        return false;
    }else if( $("#quantity").val() == ""){
        $("#quantity").focus();
        return false;
    }else if( $("#quote").val() == ""){
        $("#quote").focus();
        return false;
    }else if($('#linha-principal #cat-cotacao').val() == ''){
        $("#linha-principal #cat-cotacao").focus();
        return false;
    }else{
        return true;
    }
}

function camposServicoPreenchidos(){
    if( $("#title_service").val() == ""){
        $("#title_service").focus();
        return false;
    }else if( $("#box-cat-servico #cat-cotacao").val() == ""){
        $("#box-cat-servico #cat-cotacao").focus();
        return false;
    }else if( $("#descricao-servico").val() == ""){
        $("#descricao-servico").focus();
        return false;
    }else if( $("#tempoestimado-servico").val() == ""){
        $("#tempoestimado-servico").focus();
        return false;
    }else if( $("#provider_qtd_service").val() == ""){
        $("#provider_qtd_service").focus();
        return false;
    }else if( $("#expectativa_inicio").val() == ""){
        $("#expectativa_inicio").focus();
        return false;
    }else if( $("#expectativa_orcamento").val() == ""){
        $("#expectativa_orcamento").focus();
        return false;
    }else if( $("#deadline_date_service").val() == ""){
        $("#deadline_date_service").focus();
        return false;
    }else{
        return true;
    }
}

function tornarCamposProdutosRequeridos(tornarRequerido){
    if(tornarRequerido == true){
        $("#title_product").attr("required", "required");
        $("#provider_qtd_product").attr("required", "required");
        $("#deadline_date_product").attr("required", "required");
        $(".nacional-checked").attr("checked","checked");
        $("#cotation_item_name").attr("required", "required");
        $("#linha-principal #cat-cotacao").attr("required", "required");
        $("#quantity").attr("required", "required");
        $("#quote").attr("required", "required");
    }else{
        $("#title_product").removeAttr("required");
        $("#provider_qtd_product").removeAttr("required");
        $("#deadline_date_product").removeAttr("required");
        $("#chkbx-tempo-produto").removeAttr("required");
        $(".nacional-checked").removeAttr("checked");
        $("#cotation_item_name").removeAttr("required");
        $("#linha-principal #cat-cotacao").removeAttr("required");
        $("#quantity").removeAttr("required");
        $("#quote").removeAttr("required");
    }
}

function tornarCamposServicosRequeridos(tornarRequerido){
    if(tornarRequerido == true){
        $("#title_service").attr("required", "required");
        $("#box-cat-servico #cat-cotacao").attr("required", "required");
        $("#descricao-servico").attr("required", "required");
        $("#tempoestimado-servico").attr("required", "required");
        $("#provider_qtd_service").attr("required", "required");
        $(".hora-checked").attr("checked","checked");
        $("#expectativa_inicio").attr("required", "required");
        $("#nacional-serv-checked").attr("checked","checked");
        $("#expectativa_orcamento").attr("required", "required");
        $("#deadline_date_service").attr("required", "required");
    }else{
        $("#title_service").removeAttr("required");
        $("#box-cat-servico #cat-cotacao").removeAttr("required");
        $("#descricao-servico").removeAttr("required");
        $("#tempoestimado-servico").removeAttr("required");
        $("#provider_qtd_service").removeAttr("required");
        $(".hora-checked").removeAttr("checked");
        $("#expectativa_inicio").removeAttr("required");
        $("#nacional-serv-checked").removeAttr("checked");
        $("#expectativa_orcamento").removeAttr("required");
        $("#deadline_date_service").removeAttr("required");
    }
}

var cotacaoEscolhida = "";
// Validando o preenchimento completo dos campos do tipo da cotação escolhida
$(document).ready(function(){
    $("#buscando-produto").click(function() {
        tornarCamposServicosRequeridos(false);
        tornarCamposProdutosRequeridos(true);
        cotacaoEscolhida = "p";
    });

    $("#buscando-servico").click(function() {
        tornarCamposProdutosRequeridos(false);
        tornarCamposServicosRequeridos(true);
        cotacaoEscolhida = "s";
    });

    $('#verde').click(function(){

        if( ($("#reduzir-cotacao").prop("checked") == false && $("#especifico-cotacao").prop("checked") == false) || ($("#buscando-produto").prop("checked") == false && $("#buscando-servico").prop("checked") == false) ){
            $('#form-create-cotation').attr('onsubmit', 'return false');
            $('#result-cotacao-geral').html("");
            $('#result-cotacao-geral').append('<div class="alert alert-info alert-dismissible" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>Por favor, selecione uma das opções.</div>');

        }else if( $(".cep-cotacao").val() == ""){
            $(".cep-cotacao").focus();
        }else if( $("#provider_qtd_product").val() > 10 || $("#provider_qtd_product").val() < 1){
            $("#provider_qtd_product").focus();
        }else if(cotacaoEscolhida == "p"){
                if(camposProdutosPreenchidos() == false){
                    $('#form-create-cotation').attr('onsubmit', 'return false');
                    $('#result-cotacao').html("");
                    $('#result-cotacao').append('<div class="alert alert-info alert-dismissible" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>Por favor, preencha os campos obrigatórios.</div>');
                    camposProdutosPreenchidos();
                }else{
                    carregarLoad(true);
                    $('#form-create-cotation').attr('onsubmit', 'return true');
                }
            }else if(cotacaoEscolhida == "s"){
                if(camposServicoPreenchidos() == false){
                    $('#form-create-cotation').attr('onsubmit', 'return false');
                    $('#result-cotacao').html("");
                    $('#result-cotacao').append('<div class="alert alert-info alert-dismissible" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>Por favor, preencha os campos obrigatórios.</div>');
                    camposServicoPreenchidos();
                }else{
                    carregarLoad(true);
                    $('#form-create-cotation').attr('onsubmit', 'return true');
                }
            }
    });
});

</script>

<script>
    //add uma linha na tabela de cotação de produto
    let productsCounter = 0, indiceItem = 0;
    function productsAdd() {
        productsCounter++;
        indiceItem++;
        $('#cotacoes tbody').append(
            "<tr id='cotacao-linha-numero'>" +
                "<td class='lp-s-1'><input class='form-control cotation_item_name"+indiceItem+"' type='text' name='cotation_product[cotation_product_items][" + productsCounter + "][product][item_name]' id='cotation_item_name' placeholder='Produto'></td>" +
                "<td class='lp-s-1'><select class='form-control cat-cotacao"+indiceItem+"' style='width:165px;' id='cat-cotacao' name='cotation_product[cotation_product_items][" + productsCounter + "][product][category_item_prod]' id=''>" +
                                                "<option value='' disabled>Selecione</option>" +
                                                "<option value='29'>Nenhuma das Listadas</option>" +
                                                "<option value='1'>Apps e Jogos</option>" +
                                                "<option value='2'>Bebês</option>" +
                                                "<option value='3'>Beleza</option>" +
                                                "<option value='4'>Bolsas, Malas e Mochila</option>" +
                                                "<option value='5'>Brinquedos e Jogos</option>" +
                                                "<option value='6'>Casa</option>" +
                                                "<option value='7'>CD e Vinil</option>" +
                                                "<option value='8'>Computadores e Informática</option>" +
                                                "<option value='9'>Cozinha</option>" +
                                                "<option value='30'>Material de limpeza</option>" +
                                                "<option value='10'>DVD e Blu-Ray</option>" +
                                                "<option value='11'>Eletrodomésticos</option>" +
                                                "<option value='12'>Eletrônicos</option>" +
                                                "<option value='13'>Esporte e Aventura</option>" +
                                                "<option value='14'>Ferramentas e Materiais de Construção</option>" +
                                                "<option value='15'>Games</option>" +
                                                "<option value='16'>Jardim e Piscina</option>" +
                                                "<option value='17'>Livros</option>" +
                                                "<option value='18'>Material de Escritório e Papelaria</option>" +
                                                "<option value='19'>Móveis e Decoração</option>" +
                                                "<option value='20'>Roupas, Calçados e Joias</option>" +
                                                "<option value='21'>Saúde e Cuidados Pessoais</option>" +
                                                "<option value='22'>Lista Compras Supermercado</option>" +
                                                "<option value='23'>Médico Hospitalar</option>" +
                                                "<option value='24'>Turismo</option>" +
                                                "<option value='25'>Químico</option>" +
                                                "<option value='26'>MRO</option>" +
                                                "<option value='27'>Clube de Assinaturas</option>" +
                                                "<option value='28'>Cursos Online</option>" +
                                                "<option value='30'>Outros</option>" +
                                            "</select></td>"

                // "<td><input type='text' name='cotation_product[cotation_product_items][" + productsCounter + "][category_item_prod]' id=''></td>"
                +
                "<td class='lp-s-1'><input class='form-control' type='number' name='cotation_product[cotation_product_items][" + productsCounter + "][quantity]' id='' min='1' placeholder='Mínimo 1'></td>" +
                "<td class='lp-s-1'><input class='form-control dinheiro-real'  type='text' style='width: 70px;' name='cotation_product[cotation_product_items][" + productsCounter + "][quote]' id='' placeholder='R$ 0,00' step='0.01'></td>" +
                "<td class='lp-s-4'><input class='form-control manufacturer"+indiceItem+"' type='text' name='cotation_product[cotation_product_items][" + productsCounter + "][product][manufacturer]' id='manufacturer' placeholder='Fabricante'></td>" +
                "<td class='lp-s-4'><input class='form-control model"+indiceItem+"' type='text' name='cotation_product[cotation_product_items][" + productsCounter + "][product][model]' id='model' placeholder='Modelo'></td>" +
                // "<td class='lp-s-4'><input class='form-control' type='text' name='cotation_product[cotation_product_items][" + productsCounter + "][product][sku]' id=''></td>" +
                `<td class='lp-s-2' style="text-align: center; padding-top: 12px" id="deleterow-btn"><?=$this->Html->image("icons/cancel.png", ['class' => 'deleterow-btn', "width" => "24px", 'heigth' => "24px"])?></td>` +
            "</tr>"
        );

        var elemento = ".cat-cotacao"+indiceItem;
        $(elemento).val($(".cat-cotacao0").val());

        // console.log('$', $.fn);
        // console.log('jqueryTeste', jqueryTeste.fn);

        //mascara para R$ em cotações
        $(".dinheiro-real").maskMoney({prefix:'R$ ', allowNegative: true, thousands:'.', decimal:',', affixesStay: false});
        autocomplete(indiceItem);
    };

    $(".cat-cotacao0").change(function(){
        var elemento = "";
        for(var i = 1; i <= indiceItem; i++){
            elemento = ".cat-cotacao"+i;
            $(elemento).val($(".cat-cotacao0").val());
        }
    });


    //remove linha da tabela de cotação de produtos ao clicar na imagem de delete
    $("#cotacoes").on('click','.deleterow-btn',function(e) {
        $(this).closest("tr").remove();
    });

    //mascara para R$ em cotações
    $(".dinheiro-real").maskMoney({prefix:'R$ ', allowNegative: true, thousands:'.', decimal:',', affixesStay: false});

    let jqueryTeste = $;
    //funções para "tempo indeterminado" em prazo de conclusão
    $(document).ready(function() {
        $('#chkbx-tempo-service').click(function() {
            let ischecked= $(this).is(':checked');
            if(ischecked) {
                $('#deadline_date_service').val('Tempo indeterminado');
                $("#deadline_date_service").attr("readonly", true);
            } else {
                $("#deadline_date_service").attr("readonly", false);
                $('#deadline_date_service').val('');
            }
        })
        $('#chkbx-tempo-produto').click(function() {
            let ischecked= $(this).is(':checked');
            if(ischecked) {
                $('#deadline_date_product').val('Tempo indeterminado');
                $("#deadline_date_product").attr("readonly", true);
            } else {
                $("#deadline_date_product").attr("readonly", false);
                $('#deadline_date_product').val('');
            }
        })
    });


    $(document).ready(function($){
        $('.date').mask('00/00/0000');
        $('.cep').mask('00000-000');
        $('.phone_with_ddd').mask('(00) 0000-0000');
        $('.cellphone_with_ddd').mask('(00) 00000-0000');
        $('.placeholder').mask("00/00/0000", {placeholder: "__/__/____"});

        $('.selectonfocus').mask("00/00/0000", {selectOnFocus: true});

        jqueryTeste = $;
    });

    // Checa se a data é menor que a data atual
    $('.data-atual').blur(function () {
        let dateString = $(this).val();
        let date = moment(dateString , 'DD/MM/YYYY');
        let now = moment(moment().format('DD/MM/YYYY'), 'DD/MM/YYYY'); // só a data atual

        let isFuture = date.diff(now) > 0;
        let isToday = date.diff(now) == 0;
        let isPast = date.diff(now) < 0;
        if (isPast) {
            swal('Data Inválida, informe uma data atual.');
            $(this).val('');
        }

    })

    $('#form-create-cotation').submit(function (event) {
        let type = $('input[name=type]:checked').val();
        if (type) {
            $('#provider-qtd').val(type == 0 ? $('#provider_qtd_product').val() : $('#provider_qtd_service').val())
            $('#deadline_date').val(type == 0 ? $('#deadline_date_product').val() : $('#deadline_date_service').val())
            $('#coverage').val(type == 0 ? $('input[name=coverage_product]:checked').val() : $('input[name=coverage_service]:checked').val())
            $('#title').val(type == 0 ? $('#title_product').val() : $('#title_service').val())
        }
    });

</script>

<style>
.inputfile {
  /* visibility: hidden etc. wont work */
  width: 0.1px;
  height: 0.1px;
  opacity: 0;
  overflow: hidden;

  z-index: -1;
}
.inputfile:focus + label {
  /* keyboard navigation */
  outline: 1px dotted #000;
  outline: -webkit-focus-ring-color auto 5px;
}


</style>

<?php $this->assign('title', 'Editando Cotação');


if(!empty($cotation['cotation_product'])){
    $qtdCotationProdutosItens = count($cotation['cotation_product']['cotation_product_items']);
}else{
    $qtdCotationProdutosItens = -1;
}
?>
<input type="hidden" id="type-cotation" value="<?=$cotation['type']?>">
    <div class="dashboard-container">
        <div class="ngproc-card">
            <div class="ngproc-card-title">
                <?=$this->Html->link('< Minhas Cotações', ['action' => 'index'], ['class' => 'btn', 'id' => 'voltar']);?>
            </div>
            <div id="tela1" class="ngproc-card-content">
                <div class="row">
                        <?php if ($cotation['type'] == 0) : ?>
                            <!-- EDIÇÃO DA COTAÇÃO DE PRODUTO -->
                            <div id="partner-view-product">
                                <div>
                                    <div class="content-left col-sm-7">
                                        <div class="title-left h5 col-sm-12">
                                            Dados da cotação
                                        </div>
                                        <div class="col-sm-12">
                                            <div class="form-group has-feedback input-linha col-md-6">
                                                <label style="margin-bottom:20px" for="objetivo-cliente">Objetivo do cliente</label>
                                                <div class="opcoes-newquot">
                                                    <input class="objective-p" type="radio" id="reduzir-cotacao" name="objective" value="1" <?= $cotation['type']==0 && $cotation['objective'] == 1 ? "checked" : "" ?>>
                                                    <label for="reduzir-cotacao">Reduzir custos</label>
                                                    <br>
                                                    <input class="objective-p" type="radio" id="especifico-cotacao" name="objective" value="2" <?= $cotation['type']==0 && $cotation['objective'] == 2 ? "checked" : "" ?>>
                                                    <label for="especifico-cotacao">Itens de difícil localização</label>
                                                    <br>
                                                </div>
                                            </div>
                                            <div class="form-group has-feedback input-linha col-md-6">
                                                <label for="buscando-produto">Buscando por</label>
                                                <input type="text" class="form-control" id="buscando-produto" name="" value="Produtos" readonly="readonly">
                                            </div>
                                        </div>
                                        <div class="col-sm-12">
                                            <div class="form-group has-feedback col-md-6">
                                                <label for="name">Título *</label>
                                                <input type="text" class="form-control" id="title_product" value="<?=$cotation['title']?>" placeholder="Título da cotação" name="title_product" >
                                            </div>
                                            <div id="opcao" class="form-group has-feedback col-md-6">
                                                <label style="font-size: 13px;" id="opcao">Quantas opções de fornecedor*</label>
                                                <input type="number" class="form-control" id="provider_qtd_product" name="provider_qtd_product" value="<?=$cotation['provider_qtd']?>" placeholder="No máximo 10" min="1" max="10" />
                                            </div>
                                        </div>
                                        <div class="col-sm-12">
                                            <div class="form-group has-feedback col-md-6">
                                                <label for="prazo-conclusao-produto">Prazo para conclusão *</label>
                                                <input type="text" class="form-control date data-atual" id="prazo-conclusao-produto" value="<?= $cotation['deadline_date'] == 'Tempo indeterminado' ? 'Tempo indeterminado' : $cotation['deadline_date'] ?>" placeholder="dd/mm/aaaa" >
                                                <label>
                                                    <input type="checkbox" id="chkbx-tempo-produto" <?= $cotation['deadline_date'] == 'Tempo indeterminado' ? 'checked' : '' ?>>Tempo indeterminado
                                                </label>
                                            </div>
                                            <div class="form-group has-feedback col-md-6">
                                                <label for="abrangencia">Abrangência da cotação *</label>
                                                <br>
                                                <label class="radio-inline" style="margin-top: 7px;">
                                                    <input class="tipo-abrangencia-p" type="radio" name="coverage_product" value="Nacional" <?= $cotation['coverage'] == 'Nacional' ? 'checked' : '' ?>> Nacional
                                                </label>
                                                <label class="radio-inline" style="margin-top: 7px;">
                                                    <input class="tipo-abrangencia-p" type="radio" name="coverage_product" value="Internacional" <?= $cotation['coverage'] == 'Internacional' ? 'checked' : '' ?>> Internacional
                                                </label>
                                            </div>
                                        </div>
                                        <div class="col-sm-12">
                                            <div class="form-group has-feedback col-sm-6">
                                                <label for="name">CEP de Entrega *</label>
                                                <input type="text" class="form-control" id="address_zipcode_cotation" value="<?=$cotation['address_zipcode']?>" placeholder="Digite um cep">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="content-right float-right col-sm-5">
                                        <div class="title-left h5 col-sm-12">
                                            Anexar arquivos úteis para a cotação
                                        </div>
                                        <form id="form-anexo-cotation-product" enctype="multipart/form-data">
                                            <div class="box-anexo-new-quotation btn btn-sm form-group has-feedback col-sm-12" style="padding:3px 5px; margin-bottom:20px">
                                                <input type="file" name="anexo[]" id="anexos-file" multiple>
                                                <input type="text" name="cotation-id" id="cotation-reference-anexo" style="display:none">
                                                <input type="submit" id="btn-anexo-uteis" style="display:none">
                                            </div>
                                        </form>
                                        <label>
                                            Anexados
                                        </label>
                                        <div class='col-sm-12 anexos-listados scroll'>
                                            <?php $aux = 0; $issetAnexos = false;
                                            foreach ($cotation['cotation_attachments'] as $anexo) : ?>
                                                <?php $issetAnexos = true; ?>
                                                <div class="col-sm-12">
                                                    <script>
                                                        function deletarAnexo(anexoId) {
                                                            let body = {};
                                                            body['id'] = anexoId;
                                                            let p = $.post('<?= $this->Url->build(['action' => 'deleteAnexo']) ?>', body);
                                                            p.done(function response(data){
                                                                if (data.result == 'success') {
                                                                    swal("Anexo deletado com sucesso", {
                                                                        icon: "success"
                                                                    }).then(value => {
                                                                        window.location = "<?=$this->Url->build(['prefix' => 'client', 'controller' => 'Quotation', 'action' => 'edit', $cotation->id])?>";
                                                                    });
                                                                }else{
                                                                    swal('Houve um erro inesperado');
                                                                }
                                                            });
                                                        }
                                                    </script>
                                                    <span>
                                                        <a href="#" onclick="deletarAnexo(<?=$anexo->id?>)" style="margin-right:7px"><img src="<?= $this->Url->build('/img/icons/delete-button.png')?>" alt="Deletar" width="16" height="16"></a>
                                                        <a href="<?= $this->Url->build("/uploads/cotations/{$anexo->name}") ?>" download="<?= $anexo->name_original ?>">
                                                            <img src="<?= $this->Url->build('/img/icons/download.png')?>" alt="Download" width="14" height="14">
                                                            <span style="color:#004268"><?= $anexo->name_original ?></span>
                                                        </a>
                                                    </span>
                                                </div>
                                            <?php endforeach; ?>
                                            <?php if(!$issetAnexos) : ?>
                                                <span>Nenhum anexo encontrado</span>
                                            <?php endif;?>
                                        </div>
                                    </div>
                                </div>
                                <div id="tabela-cotacoes" class="table-responsive" style="margin-left: 15px;">
                                    <table id="cotacoes" class="table table-bordered table-responsive">
                                        <div class="scrollmenu">
                                            <thead>
                                                <tr>
                                                    <th>Nome *</th>
                                                    <th>Categoria *</th>
                                                    <th>Quantidade *</th>
                                                    <th>Orçamento Unit. *</th>
                                                    <th>Fabricante</th>
                                                    <th>Modelo</th>
                                                    <!-- <th>SKU</th> -->
                                                    <th>
                                                        <div style="cursor:pointer" onclick="productsAdd()">
                                                            <?=$this->Html->image("icons/plus-add.png", ["width" => "26px", 'heigth' => "26px", 'alt' => 'add'])?>
                                                        </div>
                                                    </th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                            <script> var arrayIdsItensCotacao = [];</script>
                                                <input type="hidden" id="qtd_itens_cotation" value="<?=count($cotation['cotation_product']['cotation_product_items'])?>">
                                                <?php foreach ($cotation['cotation_product']['cotation_product_items'] as $k => $item) : ?>
                                                    <tr id="linha<?=$k?>">
                                                    <script>
                                                        arrayIdsItensCotacao[<?=$k?>] = {
                                                            linha: "#linha<?=$k?>",
                                                            idItem: <?=$item->id?>,
                                                            excluido: false
                                                        };
                                                    </script>
                                                        <input class="idProduct" type="hidden" value="<?= $item->product_id ?>">
                                                        <input class="idCotationProductItems" type="hidden" id="item<?=$k?>" value="<?= $item->id ?>">
                                                        <td class="lp-s-1"><input class="form-control nome-product cotation_item_name<?=$k?>" type="text"  id="cotation_item_name<?=$k?>" value="<?= $item->product->item_name ?>" ></td>
                                                        <td class="lp-s-1">
                                                            <select class="form-control category cat-cotacao<?=$k?>" style="width:165px;" id="cat-cotacao<?=$k?>">
                                                                <option value="" disabled>Selecione a categoria</option>
                                                                <option value="0">Todas as categorias</option>
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
                                                        <script>
                                                            var elemento = ".cat-cotacao"+<?=$k?>+" option[value=" + <?=$item->product->category_item_prod?> + "]";
                                                            $(elemento).attr('selected','selected');
                                                        </script>
                                                        <td class="lp-s-1"><input class="form-control quantity" type="number"  id="quantity<?=$k?>" min="1" value="<?= $item->quantity ?>"></td>
                                                        <td class="lp-s-1"><input class="form-control quote dinheiro-real" type="text" id="quote<?=$k?>" placeholder="R$" step="0.01" value="<?= 'R$ '.$this->Number->format($item->quote,['places'=>2,'locale'=>'pt_BR']) ?>"></td>
                                                        <td class="lp-s-4"><input class="form-control manufacturer" type="text" id="manufacturer<?=$k?>" value="<?= $item->product->manufacturer ?>"></td>
                                                        <td class="lp-s-4"><input class="form-control model" type="text" id="model<?=$k?>" value="<?= $item->product->model ?>"></td>
                                                        <!-- <td class="lp-s-4"><input class="form-control sku" type="text" name="cotation_product[cotation_product_items][0][product][sku]" id="" value="<?= $item->product->sku ?>"></td> -->
                                                        <!-- <td class='lp-s-2' style="text-align: center; padding-top: 12px" id="deleterow-btn"><?=$this->Html->image("icons/cancel.png", ['class' => 'deleterow-btn', "width" => "24px", 'heigth' => "24px"])?></td> -->
                                                        <td class='lp-s-2' style="text-align: center; padding-top: 12px" id="deleterow-btn"><img id="detele-linha<?=$k?>" src="<?=$this->Url->build('img/icons/cancel.png')?>" width="24" height="24" alt="excluir" onclick="removerItem(<?=$k?>)" style="cursor:pointer"></td>
                                                    </tr>
                                                <?php endforeach; ?>
                                            </tbody>
                                        </div>
                                    </table>
                                </div>
                            </div>
                        <?php else : ?>
                            <div id="partner-view-service">
                                <div>
                                    <div class="content-left col-sm-7">
                                        <div class="title-left h5 col-sm-12">
                                            Dados da cotação
                                        </div>
                                        <div class="form-group has-feedback input-linha col-sm-6">
                                            <label style="margin-bottom:20px" for="objetivo-cliente">Objetivo do cliente*</label>
                                            <div class="opcoes-newquot">
                                                <input class="objective" type="radio" id="reduzir-cotacao" name="objective" value="1" <?= $cotation['type']==1 && $cotation['objective'] == 1 ? "checked" : "" ?>>
                                                <label for="reduzir-cotacao">Reduzir custos</label>
                                                <br>
                                                <input class="objective" type="radio" id="especifico-cotacao" name="objective" value="2" <?= $cotation['type']==1 && $cotation['objective'] == 2 ? "checked" : "" ?>>
                                                <label for="especifico-cotacao">Itens de difícil localização</label>
                                                <br>
                                            </div>
                                        </div>
                                        <div class="form-group has-feedback input-linha col-sm-6">
                                            <label for="buscando-produto">Buscando por</label>
                                            <input type="text" class="form-control" id="buscando-produto" value="Serviços" readonly="readonly">
                                        </div>
                                        <div class="form-group has-feedback col-sm-12">
                                            <label for="title_servico">Título*</label>
                                            <input type="text" class="form-control" id="title_servico" value="<?=$cotation['title']?>" placeholder="Título da cotação">
                                        </div>
                                        <div class="form-group has-feedback col-sm-12">
                                            <label for="cat-cotacao">Categoria*</label>
                                            <select class="form-control cat-cotacao0" id="cat-cotacao">
                                                <option value="" disabled>Selecione a categoria</option>
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
                                        <script>
                                            var elemento = ".cat-cotacao0 option[value=" + <?=$cotation->cotation_service->category?> + "]";
                                            $(elemento).attr('selected','selected');
                                        </script>
                                        <div class="form-group has-feedback col-sm-12">
                                            <label for="descricao_service">Descrição dos itens para cotação*</label>
                                            <input type="text" class="form-control" id="descricao_service" value="<?=$cotation->cotation_service->description?>">
                                        </div>
                                        <div class="form-group has-feedback col-sm-6">
                                            <label for="tempoestimado-servico">Tempo estimado do serviço*</label>
                                            <input type="text" class="form-control" id="tempoestimado-servico" value="<?=$cotation->cotation_service->service_time?>">
                                        </div>
                                        <div class="form-group has-feedback col-sm-6">
                                            <label for="provider_qtd_service">Quantas opções de fornecedor?*</label>
                                            <input type="number" class="form-control" id="provider_qtd_service" value="<?=$cotation->provider_qtd?>">
                                        </div>
                                        <div class="form-group has-feedback col-sm-6">
                                            <label for="tipo_cobranca">Tipo de cobrança*</label>
                                            <br>
                                            <label class="radio-inline" style="margin-top: 7px;">
                                                <input class="cobranca" type="radio" name="cotation_service[collection_type]" id="collection_type" value="Hora" <?= $cotation['type']==1 && $cotation->cotation_service->collection_type == 'hora' ? "checked" : "" ?>> Hora
                                            </label>
                                            <label class="radio-inline" style="margin-top: 7px;">
                                                <input class="cobranca" type="radio" name="cotation_service[collection_type]" id="collection_type" value="Serviço" <?= $cotation['type']==1 && $cotation->cotation_service->collection_type == 'serviço' ? "checked" : "" ?>> Serviço
                                            </label>
                                        </div>
                                        <div class="form-group has-feedback col-sm-6">
                                            <label for="abrangencia">Abrangência da cotação*</label>
                                            <br>
                                            <label class="radio-inline" style="margin-top: 7px;">
                                                <input class="tipo-abrangencia" type="radio" name="coverage_product" value="Nacional" <?= $cotation['coverage'] == 'Nacional' ? 'checked' : '' ?>> Nacional
                                            </label>
                                            <label class="radio-inline" style="margin-top: 7px;">
                                                <input class="tipo-abrangencia" type="radio" name="coverage_product" value="Internacional" <?= $cotation['coverage'] == 'Internacional' ? 'checked' : '' ?>> Internacional
                                            </label>
                                        </div>
                                        <div class="form-group has-feedback col-sm-6">
                                            <label for="expectativa_inicio">Expectativa de início de serviço*</label>
                                            <input type="text" class="form-control date date" id="expectativa_inicio" placeholder="dd/mm/aaaa" value="<?=$cotation->cotation_service->expectation_start?>">
                                        </div>
                                        <div class="form-group has-feedback col-sm-6">
                                            <label for="deadline_date_service">Prazo para conclusão*</label>
                                            <input type="text" class="form-control date data-atual" id="deadline_date_service" value="<?= $cotation['deadline_date'] == 'Tempo indeterminado' ? 'Tempo indeterminado' : $cotation['deadline_date'] ?>" placeholder="dd/mm/aaaa" >
                                            <label>
                                                <input type="checkbox" id="chkbx-tempo-service" <?= $cotation['deadline_date'] == 'Tempo indeterminado' ? 'checked' : '' ?>>Tempo indeterminado
                                            </label>
                                        </div>
                                        <div class="form-group has-feedback col-sm-6">
                                            <label for="expectativa_orcamento">Expectativa de orçamento*</label>
                                            <input type="text" class="form-control dinheiro-real" id="expectativa_orcamento" placeholder="R$" step="0.01" value="<?= 'R$ '.$this->Number->format($cotation->cotation_service->estimate,['places'=>2,'locale'=>'pt_BR']) ?>">
                                        </div>
                                    </div>
                                    <div class="content-right float-right col-sm-5">
                                        <div class="title-left h5 col-sm-12">
                                            Arquivos anexados
                                        </div>
                                        <form name="form-anexo"  method="post" enctype="multipart/form-data">
                                            <div class="box-anexo-new-quotation btn btn-sm form-group has-feedback col-sm-12" style="padding:3px 5px;margin-bottom: 20px">
                                                <input name="cotation_id" value="<?= $cotation['id']?>" style= "display:none">
                                                <input id="anexos-service" type="file" name="anexo[]" multiple>
                                            </div>
                                        </form>
                                        <?php foreach ($cotation['cotation_attachments'] as $anexo) : ?>
                                            <div class="col-sm-12">
                                                <a href="<?= $this->Url->build("/uploads/cotations/{$anexo->name}") ?>" download="<?= $anexo->name_original ?>"><?= $anexo->name_original ?></a>
                                                <a class="text-danger" href="<?= $this->Url->build(["action" => "deleteAttachments",  $anexo->id] ) ?>"> Excluir</a>
                                            </div>
                                        <?php endforeach; ?>
                                    </div>
                                </div>
                            </div>
                        <?php endif; ?>
                    </div>
                    <div class="row btn-partner-view-cot">
                        <button type="button" class="btn btn-success" id="btn-salvar" style="padding-left:50px;padding-right:50px;margin-right: 15px;">Salvar</button>
                        <button style="
                            padding-left: 55px;
                            padding-right: 55px;
                            color: white;
                            border-radius: 4px;
                            margin-right:15px"
                        class="btn btn-danger"
                        id="btn-cancelar">
                        Cancelar</button>
                        <?= $this->Html->link('Voltar', ['action' => 'index'], ['class' => 'btn btn-dark', 'id' => 'preto']); ?>
                    </div>
                </div>
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
$('#btn-cancelar').click(function(e){
    e.preventDefault();
    swal({
        title: "Tem certeza?",
        text: "Você está prestes a cancelar sua cotação.",
        icon: "warning",
        buttons: ['Voltar','Sim, cancelar'],
        dangerMode: true,
    }).then((willDelete) => {
        if (willDelete) {
            window.location = "<?= $this->Url->build(['action' => 'cancel-cotation', $cotation->id]) ?>";
        } else {

        }
    });
});
</script>

<script>
function validarDadosCotacaoPorFornecedor() {
    if($('#type-cotation').val() == 0){
        if ($('#title_product').val() == "") {
            $("#title_product").focus();
            return false;
        } else if ($('#provider_qtd_product').val() == "") {
            $("#provider_qtd_product").focus();
            return false;
        }else if($('#prazo-conclusao-produto').val() == ""){
            $('#prazo-conclusao-produto').focus();
            return false;
        }else if($('#address_zipcode_cotation').val() == ""){
            $('#address_zipcode_cotation').focus();
            return false;
        } else {
            // let todosItensChecked = -1;
            // let cont = 0;
            for (var i = 0; i < $('#qtd_itens_cotation').val(); i++) {
                //cont = i;
                var elementoName = "#cotation_item_name" + i,
                elementoCategory = "#cat-cotacao" + i,
                elementoQuantity = "#quantity" + i,
                elementoQuote = "#quote" + i;
                // elementoManufacturer = "#manufacturer" + i,
                // elementoModel = "#model" + i;
                if ($(elementoName).val() == "") {
                    $(elementoName).focus();
                    return false;
                } else if ($(elementoCategory).val() == "") {
                    $(elementoCategory).focus();
                    return false;
                } else if ($(elementoQuantity).val() == "" || $(elementoQuantity).val() == "0") {
                    $(elementoQuantity).focus();
                    return false;
                } else if ($(elementoQuote).val() == "") {
                    $(elementoQuote).click();
                    return false;
                // } else if ($(elementoManufacturer).val() == "") {
                //     $(elementoManufacturer).focus();
                //     return false;
                // } else if ($(elementoModel).val() == "") {
                //     $(elementoModel).focus();
                //     return false;
                } else if (i + 1 == $('#qtd_itens_cotation').val()) {
                    return true;
                }
            }
        }
    }else if($('#type-cotation').val() == 1){
        if ($("#exp-orcamento_serv").val() == "") {
            $("#exp-orcamento_serv").focus();
            return false;
        } else if ($("#exp_start_servico").val() == "") {
            $("#exp_start_servico").focus();
            return false;
        } else if ($("#tempo_estimado_serv").val() == "") {
            $("#tempo_estimado_serv").focus();
            return false;
        } else {
            return true;
        }
    }
}
</script>

<script>
var cotadorItensCotation = <?=$qtdCotationProdutosItens?>;
function removerItem(i){
    //MARCA OS ITENS JÁ SALVOS NO BANCO COMO EXCLUIDOS
    //CASO O USUÁRIO DECIDA REMOVER
    if(cotadorItensCotation <= 1){
        swal({
            title: 'Sinto muito',
            text: 'A cotação precisa ter ao menos um item',
            icon: 'error',
            button: "Tudo bem"
        });
    }else{
        swal({
            title: "Você tem certeza?",
            text: "Este item será excluído e não poderá ser recuperado.",
            icon: "warning",
            buttons: ["Cancelar", "Excluir"],
            dangerMode: true,
        })
        .then((willDelete) => {
            if (willDelete) {
                arrayIdsItensCotacao[i].excluido = true;
                p = $.post(`<?= $this->Url->build(['action' => 'deleteItemCotation']) ?>`, arrayIdsItensCotacao[i]);
                carregarLoad(true);
                p.done(function response(data) {
                    carregarLoad(false);
                    if (data.result == 'success') {
                        swal("Item excluido com sucesso", {
                            icon: "success"
                        }).then(value => {
                            var elemento = "#detele-linha"+i;
                            $(elemento).closest("tr").remove();
                            cotadorItensCotation--;
                            //window.location.href = '<?= $this->Url->build(['action' => 'index']) ?>';
                        });
                    } else {
                        swal("Falha ao enviar cotação", {
                            icon: "error"
                        }).then(value => {
                            //window.location.href = '<?= $this->Url->build(['action' => 'index']) ?>';
                        });
                    }
                });
            }
        });
    }
}
//remove linha da tabela de cotação de produtos ao clicar na imagem de delete
$("#cotacoes").on('click','.deleterow-btn',function(e) {
    if(<?=$cotation['type']?> == 0){
        if(cotadorItensCotation <= 1){
            swal({
                title: 'Sinto muito',
                text: 'A cotação precisa ter ao menos um item',
                icon: 'error',
                button: "Tudo bem"
            });
        }else{
            $(this).closest("tr").remove();
            cotadorItensCotation--;
        }
    }
});

//add uma linha na tabela de cotação de produto
if(<?=$cotation['type']?> == 0) var temp = <?=$qtdCotationProdutosItens?> -1;
let productsCounter = temp, indiceItem = temp;
function productsAdd() {
    productsCounter++;
    indiceItem++;
    $('#cotacoes tbody').append(
        "<tr id='linha"+indiceItem+"'>" +
            "<input class='idProduct' type='hidden' value='null'>" +
            "<input class='idCotationProductItems' type='hidden' value='null'>" +
            "<td class='lp-s-1'><input class='form-control nome-product cotation_item_name"+indiceItem+"' type='text' name='cotation_product[cotation_product_items][" + productsCounter + "][product][item_name]' id='cotation_item_name'></td>" +
            "<td class='lp-s-1'><select class='form-control category cat-cotacao"+indiceItem+"' style='width:165px;' id='cat-cotacao' name='cotation_product[cotation_product_items][" + productsCounter + "][product][category_item_prod]' id=''>" +
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
            "<td class='lp-s-1'><input class='form-control quantity' type='number' name='cotation_product[cotation_product_items][" + productsCounter + "][quantity]' id='' min='1'></td>" +
            "<td class='lp-s-1'><input class='form-control quote dinheiro-real'  type='text' style='width: 70px;' name='cotation_product[cotation_product_items][" + productsCounter + "][quote]' id='' placeholder='R$' step='0.01'></td>" +
            "<td class='lp-s-4'><input class='form-control manufacturer manufacturer"+indiceItem+"' type='text' name='cotation_product[cotation_product_items][" + productsCounter + "][product][manufacturer]' id='manufacturer'></td>" +
            "<td class='lp-s-4'><input class='form-control model model"+indiceItem+"' type='text' name='cotation_product[cotation_product_items][" + productsCounter + "][product][model]' id='model'></td>" +
            // "<td class='lp-s-4'><input class='form-control sku' type='text' name='cotation_product[cotation_product_items][" + productsCounter + "][product][sku]' id=''></td>" +
            `<td class='lp-s-2' style="text-align: center; padding-top: 12px" id="deleterow-btn"><?=$this->Html->image("icons/cancel.png", ['class' => 'deleterow-btn', "width" => "24px", 'heigth' => "24px"])?></td>` +
        "</tr>"
    );
    cotadorItensCotation++;
    var elemento = ".cat-cotacao"+indiceItem;
    $(elemento).val($(".cat-cotacao0").val());

    // console.log('$', $.fn);
    // console.log('jqueryTeste', jqueryTeste.fn);

    //mascara para R$ em cotações
    $(".dinheiro-real").maskMoney({prefix:'R$ ', allowNegative: true, thousands:'.', decimal:',', affixesStay: false});
    autocomplete(indiceItem);
}
</script>

<script>
let jqueryTeste = $;

// Máscaras
$(document).ready(function($){
    $('.date').mask('00/00/0000');
    $('.cep').mask('00000-000');
    $('.phone_with_ddd').mask('(00) 0000-0000');
    $('.cellphone_with_ddd').mask('(00) 00000-0000');
    $('.placeholder').mask("00/00/0000", {placeholder: "__/__/____"});

    $('.selectonfocus').mask("00/00/0000", {selectOnFocus: true});

    jqueryTeste = $;

    //mascara para R$ em cotações
    $(".dinheiro-real").maskMoney({
        prefix: "R$ ",
        decimal: ",",
        thousands: ".",
        affixesStay: true,
    });
});

//funções para "tempo indeterminado" em prazo de conclusão
$(document).ready(function() {
    if($('#chkbx-tempo-produto').is(':checked')){
        $('#prazo-conclusao-produto').val('Tempo indeterminado');
        $("#prazo-conclusao-produto").attr("readonly", true);
    }

    $('#chkbx-tempo-produto').click(function() {
        let ischecked= $('#chkbx-tempo-produto').is(':checked');
        if(ischecked) {
            $('#prazo-conclusao-produto').val('Tempo indeterminado');
            $("#prazo-conclusao-produto").attr("readonly", true);
        } else {
            $("#prazo-conclusao-produto").attr("readonly", false);
            $('#prazo-conclusao-produto').val('');
        }
    });
});

//funções para "tempo indeterminado" em prazo de conclusão
$(document).ready(function() {
    if($('#chkbx-tempo-service').is(':checked')){
        $('#deadline_date_service').val('Tempo indeterminado');
        $("#deadline_date_service").attr("readonly", true);
    }

    $('#chkbx-tempo-service').click(function() {
        let ischecked= $('#chkbx-tempo-service').is(':checked');
        if(ischecked) {
            $('#deadline_date_service').val('Tempo indeterminado');
            $("#deadline_date_service").attr("readonly", true);
        } else {
            $("#deadline_date_service").attr("readonly", false);
            $('#deadline_date_service').val('');
        }
    });
});

var arrayItens = [];
function montarArrayCotationProductItems(){
    var aux = 2;
    for(var i = 0; i < $('#cotacoes tbody tr').length; i++){
        aux++;
        var elemento = "#cotacoes tbody tr:nth-child("+aux+")",
        id = elemento + " .idCotationProductItems",
        quantity = elemento + " .quantity",
        quote = elemento + " .quote",
        product_id = elemento + " .idProduct",
        item_name = elemento + " .nome-product",
        model = elemento + " .model",
        category_item_prod = elemento + " .category",
        manufacturer = elemento + " .manufacturer",
        sku = elemento + " .sku";
        arrayItens[i] = {
            id: $(id).val(),
            cotation_product_id: <?= !empty($cotation['cotation_product']) ? $cotation['cotation_product']['id'] : "null";?>,
            quantity: $(quantity).val(),
            quote: $(quote).val(),
            product_id: $(product_id).val(),
            provider_id: "null",
            products: {
                id: $(product_id).val(),
                item_name: $(item_name).val(),
                model: $(model).val(),
                category_item_prod: $(category_item_prod).val(),
                manufacturer: $(manufacturer).val(),
                sku: $(sku).val()
            }
        }
    }
    return arrayItens;
}

function enviarAnexosCotation(cotation_id) {
    let elemento = "#cotation-reference-anexo";
    $(elemento).val(cotation_id);
    elemento = "#form-anexo-cotation-product";
    let formdata = new FormData($(elemento)[0]);
    let link = "<?= $this->Url->build(['action' => 'saveAnexosCotation']) ?>";
    $.ajax({
        type: 'POST',
        url: link,
        data: formdata ,
        processData: false,
        contentType: false,
    }).done(function (data) {
       console.log('Anexou: ', data)
    });
}

//Salvando a edição da cotação
$("#btn-salvar").click(function(){
    if(!validarDadosCotacaoPorFornecedor()){
        swal('Preencha os campos')
    }else{
        var cotation = {};
        let p = "";

        if(<?=$cotation['type']?> == 0){
            cotationProdItem = montarArrayCotationProductItems();
            //Editar cotação de produto
            cotation = {
                id: <?=$cotation['id']?>,
                title: $('#title_product').val(),
                type: "0",
                provider_qtd: $('#provider_qtd_product').val(),
                objective: $('.objective-p:checked').val(),
                deadline_date: $('#prazo-conclusao-produto').val(),
                status: "0",
                created: "",
                coverage: $('.tipo-abrangencia-p:checked').val(),
                user_id: <?=$cotation['user_id']?>,
                main_cotation_id: "",
                // address_zipcode: $('#address_zipcode_cotation').val(),
                cotation_product: {
                    id: <?= !empty($cotation['cotation_product']) ? $cotation['cotation_product']['id'] : "null"; ?>,
                    estimate: "",
                    cotation_id: <?=$cotation['id']?>,
                    cotation_product_items: cotationProdItem
                },
                cotation_attachments: ""
            }
        }else{
            //Editar cotação de serviço

            cotation = {
                id: <?=$cotation['id']?>,
                title: $('#title_servico').val(),
                type: "1",
                provider_qtd: $('#provider_qtd_service').val(),
                objective: $('.objective:checked').val(),
                deadline_date: $('#deadline_date_service').val(),
                status: "0",
                created: "",
                coverage: $('.tipo-abrangencia:checked').val(),
                user_id: <?=$cotation['user_id']?>,
                main_cotation_id: "",
                cotation_attachments: "" ,
                cotation_service: {
                    id: <?= !empty($cotation['cotation_service']) ? $cotation['cotation_service']['id'] : "null";?>,
                    description: $('#descricao_service').val(),
                    service_time: $('#tempoestimado-servico').val(),
                    category: $('#cat-cotacao').val(),
                    collection_type: $('.cobranca:checked').val(),
                    expection_start: $('#expectativa_inicio').val(),
                    estimate: $('#expectativa_orcamento').val(),
                    cotation_id:<?=$cotation['id']?>
                }

            }
        }
        console.log('Cotation: ', cotation)
        p = $.post('<?= $this->Url->build(['action' => 'editCotation']) ?>', cotation);
        carregarLoad(true);
        p.done(function response(data) {
            carregarLoad(false);
            if (data.result == 'success') {
                enviarAnexosCotation(cotation.id)
                swal({
                    title: "Cotação Editada",
                    text: "Deseja continuar editando?",
                    icon: "success",
                    buttons: ['Não, finalizar','Sim, continuar'],
                }).then((willDelete) => {
                    if (willDelete) {
                        window.location = "<?=$this->Url->build(['prefix' => 'client', 'controller' => 'Quotation', 'action' => 'edit', $cotation->id])?>";
                    } else {
                        window.location.href = '<?= $this->Url->build(['action' => 'index']) ?>';
                    }
                });
            } else {
                console.error('DEU RUIM: ', data.errors)
                swal("Falha ao enviar cotação", {
                    icon: "error"
                }).then(value => {
                    //window.location.href = '<?= $this->Url->build(['action' => 'index']) ?>';
                });
            }
        });
    }
});
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
</script> -->
<style>
@media (max-width: 900px)  {
.table-responsive{
        position: inherit!important;

    }

}
</style>

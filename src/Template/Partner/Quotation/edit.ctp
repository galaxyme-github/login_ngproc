<?php $this->assign('title', 'Editando Cotação');

if (!empty($cotation['cotation_product'])) {
    $qtdCotationProdutosItens = count($cotation['cotation_product']['cotation_product_items']);
} else {
    $qtdCotationProdutosItens = -1;
}

function formatarNomeFornecedor($texto){
    $nameArray = preg_split('" "', strtoupper($texto));

    $name = $nameArray[0];
    $fornecedorName = $name;
    if(count($nameArray) > 1){
        $lastName = str_split($nameArray[1]);
        $fornecedorName = $fornecedorName . " " . $lastName[0] . ".";
    }
    return $fornecedorName;
}
?>

<input type="hidden" id="ativar-tela" value="<?=isset($_GET['f']) ? $_GET['f'] : '' ?>">

<div class="dashboard-container">
    <div class="ngproc-card">
        <div class="ngproc-card-title">
            <div>
                <?= $this->Html->link('< Meus Envios', ['action' => '../sent'], ['class' => 'btn', 'id' => 'voltar']); ?>
            </div>
            <div class="ngproc-card-title-abas">
                <nav class="nav_tabs">
                    <ul id="nav_tabs_ul">
                        <!-- As abas dos fornecedores aparecerão aqui -->
                        <?php if($cotation->type == 1) : ?>
                            <?php
                            $cont = 0;
                            $nomeFornecedor = '';
                            foreach ($cotation->cotation_service as $item) : ?>
                            <?php
                                foreach ($cotation->cotation_providers as $ct_provider){
                                    if($item->provider_id == $ct_provider->provider->id){
                                        $nomeFornecedor = $ct_provider->provider->name;
                                    }
                                }
                            ?>
                                <li id="aba-f<?=$cont?>"><input type="radio" name="tabs" class="rd_tabs" id="tab<?=$cont?>" <?= $cont == 0 ? 'checked' : '' ?>><label class="active" for="tab<?=$cont?>" onclick="trocarDeTela(<?=$cont?>)"><?= formatarNomeFornecedor($nomeFornecedor)?></label></li>
                                <?php $cont++?>
                            <?php endforeach; ?>
                        <?php endif; ?>

                        <?php if($cotation->type == 0) : ?>
                        <?php $arrayProvidersCotation = []; ?>
                            <?php
                            $cont = 0;
                            $nomeFornecedor = '';
                            foreach ($cotation->cotation_providers as $k => $ct_provider): ?>
                                <li id="aba-f<?=$cont?>"><input type="radio" name="tabs" class="rd_tabs" id="tab<?=$cont?>" <?= $cont == 0 ? 'checked' : '' ?>><label class="active" for="tab<?=$cont?>" onclick="trocarDeTela(<?=$cont?>)"><?= formatarNomeFornecedor($ct_provider->provider->name)?></label></li>
                                <?php $cont++?>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </ul>
                </nav>
            </div>
        </div>
        <input type="hidden" id="type-cotation" value="<?=$cotation->type?>">
        <input type="hidden" id="id-cotation" value="<?=$cotation->id?>">
            <!--
                ====================================================
                            COTAÇÃO DE SERVIÇO
                ====================================================
            -->
            <?php if($cotation->type == 1) : ?>
            <input type="hidden" id="qtd_envios_servico" value="<?= count($cotation['cotation_service']) ?>">
            <!-- TELA - SERVIÇO -->
            <?php $cont = 0;
            foreach ($cotation['cotation_service'] as $k => $ct_service) : ?>
            <div id="tela<?=$cont?>" class="ngproc-card-content" style="padding-left:25px">
            <input type="hidden" id="cotation-service-id<?=$cont?>" value="<?=$ct_service->id?>">
                <div class="row">
                    <div id="partner-view-service">
                        <div>
                            <div class="content-left col-sm-7">
                                <div class="title-left h5 col-sm-12">
                                    Dados da cotação
                                </div>
                                <div class="row">
                                    <div class="form-group has-feedback input-linha col-sm-6">
                                        <label>Título</label>
                                        <input type="text" class="form-control" value="<?= $cotation->title ?>" readonly="readonly">
                                    </div>
                                    <div class="form-group has-feedback input-linha col-sm-6">
                                        <label >Categoria</label>
                                        <input type="text" class="form-control" value="<?=$ct_service->getCategoryName()?>" readonly="readonly">
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="form-group has-feedback input-linha col-sm-6">
                                        <label>Descrição</label>
                                        <input type="text" class="form-control" value="<?= $ct_service->description; ?>" readonly="readonly">
                                    </div>
                                    <div class="form-group has-feedback input-linha col-sm-6">
                                        <label>Abrangência da cotação</label>
                                        <input type="text" class="form-control" value="<?= $cotation->coverage; ?>" readonly="readonly">
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="form-group has-feedback input-linha col-sm-6">
                                        <label>Tipo de cobrança</label>
                                        <input type="text" class="form-control" value="<?= $ct_service->collection_type; ?>" readonly="readonly">
                                    </div>
                                    <div class="form-group has-feedback input-linha col-sm-6">
                                        <label>Prazo para conclusão</label>
                                        <input type="text" class="form-control" value="<?= $cotation->deadline_date; ?>" readonly="readonly">
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="form-group has-feedback col-sm-6">
                                        <label>Expectativa de orçamento</label>
                                        <input type="text" class="form-control" id="estimate<?=$cont?>" placeholder="R$ 0,00" value="<?= "R$ " . $this->Number->format($ct_service->estimate, ['places' => 2, 'locale' => 'pt_BR']) ?>">
                                    </div>
                                    <div class="form-group has-feedback col-sm-6">
                                        <label>Expectativa de início de serviço</label>
                                        <input type="text" class="form-control date" id="expectation_start<?=$cont?>" placeholder="dd/mm/aaaa" value="<?= $ct_service->expectation_start; ?>">
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="form-group has-feedback col-sm-6">
                                        <label>Tempo estimado de demanda do serviço</label>
                                        <input type="text" class="form-control" id="service_time<?=$cont?>" placeholder="Ex.: 6 dias" value="<?= $ct_service->service_time; ?>">
                                    </div>
                                </div>
                            </div>
                            <div class="content-right float-right col-sm-5">
                                <div class="title-left h5 col-sm-12">
                                    Arquivos anexados
                                </div>
                                <form name="form-anexo" enctype="multipart/form-data">
                                    <div class="box-anexo-new-quotation btn btn-sm form-group has-feedback col-sm-12" style="padding:3px 5px">
                                        <input id="anexos-service" type="file" name="anexo[]" multiple>
                                    </div>
                                </form>
                                <?php foreach ($cotation['cotation_attachments'] as $anexo) : ?>
                                    <div class="col-sm-12">
                                        <a href="<?= $this->Url->build("/uploads/cotations/{$anexo->name}") ?>" download="<?= $anexo->name_original ?>"><?= $anexo->name_original ?></a>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row btn-partner-view-cot">
                    <input type="button" class="btn btn-danger" id="btn-cancelar-envio" value="Cancelar envio" style="margin-right: 15px;padding:6px 35px">
                    <input type="button" class="btn btn-success" id="verde" value="Salvar edição" style="margin-right: 15px;" onclick="editarDadosCotacao(<?=$cont?>)">
                    <!-- <input type="button" class="btn btn-dark" id="preto" value="Voltar" style="margin-right: 15px;"> -->
                    <?= $this->Html->link('Voltar', ['action' => '../sent'], ['class' => 'btn btn-dark', 'id' => 'preto']); ?>
                </div>
            </div>
            <?php $cont++; ?>
            <?php endforeach; ?>
            <?php endif;?>
            <!--
                ====================================================
                            COTAÇÃO DE PRODUTO
                ====================================================
            -->
            <?php if($cotation->type == 0) : ?>
            <input type="hidden" id="qtd-envios-produtos" value="<?=count($cotation->cotation_providers)?>">
            <!-- TELA 1 - PRODUTO -->
            <?php
            $cont = 0;
            $idItem = 0;
            foreach ($arrayProviderItems as $k => $ct_provider): ?>
            <div id="tela<?=$cont?>" class="ngproc-card-content" style="padding-left:25px">
            <input type="hidden" id="cotation-provider-id<?=$cont?>" value="<?= $ct_provider['cotation_providers']['id'] ?>">
            <input type="hidden" id="provider-id<?=$cont?>" value="<?= $ct_provider['cotation_providers']['provider_id'] ?>">
                <div class="row">
                    <div id="partner-view-product">
                        <div>
                            <div class="content-left col-sm-7">
                                <div class="title-left h5 col-sm-12">
                                    Dados da cotação
                                </div>
                                <div class="row">
                                    <div class="form-group has-feedback input-linha col-sm-6">
                                        <label for="objetivo-cliente">Objetivo do cliente</label>
                                        <input type="text" class="form-control" value="<?php
                                            if ($cotation->type == 0) {
                                                switch ($cotation->objective) {
                                                    case '1':
                                                        echo "Reduzir custos";
                                                        break;
                                                    default:
                                                        echo "Itens de difícil localização";
                                                        break;
                                                }
                                            } ?>" readonly="readonly">
                                    </div>
                                    <div class="form-group has-feedback input-linha col-sm-6">
                                        <label>Buscando por</label>
                                        <input type="text" class="form-control" value="Produtos" readonly="readonly">
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="form-group has-feedback input-linha col-sm-6">
                                        <label>Quantidade de fornecedores</label>
                                        <input type="text" class="form-control" value="<?= "No máximo " . $cotation->provider_qtd ?>" readonly="readonly">
                                    </div>
                                    <div class="form-group has-feedback input-linha col-sm-6">
                                        <label>Expectativa de orçamento total</label>
                                        <input type="text" class="form-control" value="<?php
                                            if ($cotation->type == 0) {
                                                $orcamento = 0;
                                                foreach ($cotation->cotation_product->cotation_product_items as $item) {
                                                    if($ct_provider['cotation_providers']['provider_id'] == $item->provider_id) $orcamento = $orcamento + ($item->quote * $item->quantity);
                                                }
                                                echo 'R$ ' . $this->Number->format($orcamento, ['places' => 2, 'locale' => 'pt_BR']);
                                            } ?>" readonly="readonly">
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="form-group has-feedback input-linha col-sm-6">
                                        <label >Prazo para conclusão</label>
                                        <input type="text" class="form-control" value="<?= $cotation->deadline_date; ?>" readonly="readonly">
                                    </div>
                                    <div class="form-group has-feedback input-linha col-sm-6">
                                        <label>Abrangência da cotação</label>
                                        <input type="text" class="form-control" value="<?= $cotation->coverage; ?>" readonly="readonly">
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="form-group has-feedback col-sm-6">
                                        <label for="prazo-entrega<?=$cont?>">Prazo de entrega (dias) *</label>
                                        <input type="number" class="form-control deadline date" id="prazo-entrega<?=$cont?>"  min="1" placeholder="Apenas números" style="padding-right: 5px" value="<?=$ct_provider['cotation_providers']['deadline']?>">
                                    </div>
                                    <div class="form-group has-feedback col-sm-6">
                                        <label for="cost_freight<?=$cont?>">Valor do frete *</label>
                                        <input type="text" class="form-control dinheiro-real cost" id="cost_freight<?=$cont?>" placeholder="R$ 0,00" value="<?='R$ ' . $this->Number->format($ct_provider['cotation_providers']['cost'], ['places' => 2, 'locale' => 'pt_BR'])?>">
                                    </div>
                                </div>
                            </div>
                            <div class="content-right float-right col-sm-5">
                                <div class="title-left h5 col-sm-12">
                                    Anexar arquivos úteis para a cotação *
                                </div>
                                <form id="form-anexo-uteis-fornecedor-<?=$cont?>" enctype="multipart/form-data">
                                    <div class="box-anexo-new-quotation btn btn-sm form-group has-feedback col-sm-12" style="padding:3px 5px; margin-bottom:20px">
                                        <input type="file" name="anexo[]" id="anexos-file-<?=$cont?>" multiple>
                                        <input type="text" name="cotation-id" id="cotation-reference-anexo-<?=$cont?>" style="display:none">
                                        <input type="text" name="provider-id" id="provider-reference-anexo-<?=$cont?>" style="display:none">
                                        <input type="submit" id="btn-anexo-uteis-fornecedor" style="display:none">
                                    </div>
                                </form>
                                <label>Anexados</label>
                                <div class='col-sm-12 anexos-listados scroll'>
                                    <?php $aux = 0; $issetAnexos = false;
                                    foreach ($cotation['cotation_attachments'] as $anexo) : ?>
                                    <?php if ($anexo['provider_id'] == $ct_provider['cotation_providers']['provider_id']) : ?>
                                        <?php $issetAnexos = true; ?>
                                        <input type="hidden" id="qtd-anexos-<?=$cont?>" value="<?=$aux++?>">
                                        <div class="col-sm-12">
                                            <script>

                                                function deletarAnexo(anexoId, tela) {
                                                    let elemento = "#qtd-cotation-product-itens" + tela;
                                                    let aux = 0, qtd_itens = $(elemento).val();
                                                    for (let i = 0; i < qtd_itens; i++) {
                                                            let elementoLink = "#link-"+tela+'-'+i;
                                                        if ($(elementoLink).val() == ""){
                                                            aux++;
                                                        }
                                                    }
                                                    elemento = "#qtd-anexos-" + tela;
                                                    // alert('Campos preenchido: '+ aux);

                                                    if($(elemento).val() <= 1 && aux >= qtd_itens){
                                                        swal(`Você precisa de ao menos um anexo para seu fornecedor.
                                                        Para deletar o anexo, preencha o campo link de todos os itens.`,{
                                                            icon: "info"
                                                        })
                                                    }else{
                                                        let body = {};
                                                        body['id'] = anexoId;
                                                        let p = $.post('<?= $this->Url->build(['action' => 'deleteAnexo']) ?>', body);
                                                        p.done(function response(data){
                                                            if (data.result == 'success') {
                                                                swal("Anexo deletado com sucesso", {
                                                                    icon: "success"
                                                                }).then(value => {
                                                                    window.location = "<?=$this->Url->build(['prefix' => 'partner', 'controller' => 'Quotation', 'action' => 'edit', $cotation->id])?>?f="+tela;
                                                                });
                                                            }else{
                                                                swal('Houve um erro inesperado');
                                                            }
                                                        });
                                                    }
                                                }
                                            </script>
                                            <span>
                                                <a href="#" onclick="deletarAnexo(<?=$anexo->id?>, <?=$cont?>)" style="margin-right:7px"><img src="<?= $this->Url->build('/img/icons/delete-button.png')?>" alt="Deletar" width="16" height="16"></a>
                                                <a href="<?= $this->Url->build("/uploads/cotations/{$anexo->name}") ?>" download="<?= $anexo->name_original ?>">
                                                    <img src="<?= $this->Url->build('/img/icons/download.png')?>" alt="Download" width="14" height="14">
                                                    <span style="color:#004268"><?= $anexo->name_original ?></span>
                                                </a>
                                            </span>
                                        </div>
                                    <?php endif;?>
                                    <?php endforeach; ?>
                                    <?php if(!$issetAnexos) : ?>
                                        <span>Nenhum anexo encontrado</span>
                                    <?php endif;?>
                                    <input type="hidden" id="existe-anexados-<?=$cont?>" value="<?= !$issetAnexos ? "0" : "1" ?>">
                                </div>
                            </div>
                        </div>
                        <div id="tabela-cotacoes" class="table-responsive" style="margin-left: 15px;">
                            <table id="cotacoes" class="table table-bordered table-responsive">
                                <div class="scrollmenu">
                                    <thead>
                                        <tr>
                                            <th>Nome</th>
                                            <th>Categoria</th>
                                            <th>Quantidade</th>
                                            <th>Orçamento Unitário *</th>
                                            <th>Link *
                                                <a  href="#"
                                                    id="mytooltip"
                                                    data-toggle="tooltip"
                                                    title="Caso não anexe arquivos relacionados a este fornecedor, você precisará preencher o campo link, item por item."
                                                    data-placement="bottom"
                                                    style="margin-left:10px">
                                                    <?= $this->Html->image('icons/info.png', ["width" => "16px", 'heigth' => "16px"]) ?>
                                                </a>
                                            </th>
                                            <th>Fabricante</th>
                                            <th>Modelo</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($ct_provider['item'] as $index => $item) : ?>
                                            <script>//alert('Passei pela tela ' + <?=$cont?> + ' item ' + <?=$index?>)</script>
                                            <input type="hidden" id="cotation-product-item-id-<?= $cont . '-' .$index ?>" value="<?= $item->id ?>">
                                            <tr>
                                                <td><?= $item->product->item_name ?></td>
                                                <td style="text-align:center"><?= $item->product->getCategoryName() ?></td>
                                                <td class="lp-s-1"><input readonly class="form-control quantity" type="number" id="<?= 'quantity-'.$cont.'-' . $index ?>" value="<?= $item->quantity ?>" placeholder="Máximo <?= $item->quantity ?>" min="0" max="<?= $item->quantity ?>" ></td>
                                                <td class="lp-s-1"><input required class="dinheiro-real form-control quote" type="text" id="<?= 'quote-'.$cont.'-'. $index ?>" value="<?="R$ " . $this->Number->format($item->quote, ['places' => 2, 'locale' => 'pt_BR']); ?>" placeholder="<?="R$ " . $this->Number->format($item->quote, ['places' => 2, 'locale' => 'pt_BR']); ?>" step="0.01"></td>
                                                <td><input class="form-control" type="text" id="<?= 'link-' .$cont.'-'. $index  ?>" placeholder="Link do item" value="<?=$item->link_item?>"></td>
                                                <td style="text-align:center"><?= $item->product->manufacturer ?></td>
                                                <td style="text-align:center"><?= $item->product->model ?></td>
                                            </tr>
                                        <?php endforeach; ?>
                                        <input type="hidden" id="qtd-cotation-product-itens<?=$cont?>" value="<?=count($ct_provider['item'])?>">
                                    </tbody>
                                </div>
                            </table>
                        </div>
                    </div>
                </div>
                <div class="row btn-partner-view-cot">
                    <input type="button" class="btn btn-danger" id="btn-cancelar-envio" value="Cancelar Envio" style="margin-right: 15px;padding:6px 35px">
                    <input type="button" class="btn btn-success" id="verde" value="Editar" style="margin-right: 15px;" onclick="editarDadosCotacao(<?=$cont?>)">
                    <!-- <input type="button" class="btn btn-dark" id="preto" value="Voltar" style="margin-right: 15px;"> -->
                    <?= $this->Html->link('Voltar', ['action' => '../sent'], ['class' => 'btn btn-dark', 'id' => 'preto']); ?>
                </div>
            </div>
            <?php $cont++; ?>
            <?php endforeach; ?>
            <?php endif;?>
    </div>
</div>
</div>
<!-- jQuery 3 -->
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
<!-- <script src="<?= $this->Url->build('bower_components/jquery/dist/jquery.min.js') ?>"></script> -->
<!--Ajax-->
<script src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.js"></script>
<!-- <script src="<?= $this->Url->build('bower_components/jquery-ui/jquery-ui.min.js') ?>"></script> -->
<!-- jQuery Mask Plugin -->
<script src="<?= $this->Url->build('bower_components/jQuery-Mask-Plugin-master/dist/jquery.mask.min.js') ?>"></script>
<!-- Sweetalert -->
<script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
<!-- Bootstrap -->
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.0/js/bootstrap.min.js"></script>
<!-- jquery-maskmoney -->
<script src="<?= $this->Url->build('bower_components/plentz-jquery-maskmoney-cdbeeac/dist/jquery.maskMoney.js') ?>"></script>
<!-- Moment.js -->
<script src="<?= $this->Url->build('bower_components/moment/min/moment-with-locales.min.js') ?>"></script>
<!-- Sweetalert -->
<script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>

<script>
$(document).ready(function(){
    if($('#type-cotation').val() == 0){
        for (let i = 0; i < $('#qtd-envios-produtos').val(); i++) {
            let element = '#tela' + i;
            if(i == 0){
                $(element).show();
            }else{
                $(element).hide();
            }
        }
    }else if($('#type-cotation').val() == 1){
        for (let i = 0; i < $('#qtd_envios_servico').val(); i++) {
            let element = '#tela' + i;
            if(i == 0){
                $(element).show();
            }else{
                $(element).hide();
            }
        }
    }
});
</script>

<!-- <script>
function validarDadosCotacaoPorFornecedor(tela,cont) {
    let elemento = '';
    if($('#type-cotation').val() == 0){
        elemento = tela + " #prazo-entrega" + cont;
        if ($(elemento).val() == "")
            $(elemento).focus();
            return false;
        } else {
            elemento = tela + " #cost_freight" + cont;
            if ($(elemento).val() == "") {
                $(elemento).focus();
                return false;
            }else{
                elemento = tela + " .qtd-cotation-product-itens";
                for (var i = 0; i < $(elemento).val(); i++) {
                    var elementoQuantity = tela + " #quantity" + i,
                        elementoQuote = tela + " #quote" + i;

                    if ($(elementoQuantity).val() == "") {

                        $(elementoQuantity).focus();
                        return false;
                    } else if ($(elementoQuote).val() == "") {

                        $(elementoQuote).focus();
                        return false;
                    } else if (i + 1 == $(elemento).val()) {
                        return true;
                    }
                }
            }
        }
    }else if($('#type-cotation').val() == 1){
        elemento = tela + " #estimate" + cont;
        if ($(elemento).val() == "") {
            $(elemento).focus();
            return false;
        } else {
            elemento = tela + " #expectation_start" + cont;
            if ($(elemento).val() == "") {
                $(elemento).focus();
                return false;
            } else {
                elemento = tela + " #service_time" + cont;
                if ($(elemento).val() == "") {
                    $(elemento).focus();
                    return false;
                } else {
                    return true;
                }

            }

        }
    }
}
</script> -->


<script>
function trocarDeTela(tela){
    if($('#type-cotation').val() == 0){
        let element = '#tela' + tela;
        $(element).show();
        $('#tela-atual').val(element);
        for (let i = 0; i < $('#qtd-envios-produtos').val(); i++) {
            element = '#tela' + i;
            if(i != tela){
                $(element).hide();
            }
        }

    }else if($('#type-cotation').val() == 1){
        let element = '#tela' + tela;
        $(element).show();
        $('#tela-atual').val(element);
        for (let i = 0; i < $('#qtd_envios_servico').val(); i++) {
            element = '#tela' + i;
            if(i != tela){
                $(element).hide();
            }
        }
    }
}

$(document).ready(function() {
    if($('#ativar-tela').val() != ''){
        trocarDeTela($('#ativar-tela').val());
        let elemento = '#tab' + $('#ativar-tela').val();
        $(elemento).prop('checked', true);
    }
});
</script>

<script>
    let jqueryTeste = $;

    // Máscaras
    $(document).ready(function($) {
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
        if ($('#chkbx-tempo-produto').is(':checked')) {
            $('#prazo-conclusao-produto').val('Tempo indeterminado');
            $("#prazo-conclusao-produto").attr("readonly", true);
        }

        $('#chkbx-tempo-produto').click(function() {
            let ischecked = $('#chkbx-tempo-produto').is(':checked');
            if (ischecked) {
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
        if ($('#chkbx-tempo-service').is(':checked')) {
            $('#deadline_date_service').val('Tempo indeterminado');
            $("#deadline_date_service").attr("readonly", true);
        }

        $('#chkbx-tempo-service').click(function() {
            let ischecked = $('#chkbx-tempo-service').is(':checked');
            if (ischecked) {
                $('#deadline_date_service').val('Tempo indeterminado');
                $("#deadline_date_service").attr("readonly", true);
            } else {
                $("#deadline_date_service").attr("readonly", false);
                $('#deadline_date_service').val('');
            }
        });
    });
</script>
<script>
$('#btn-cancelar-envio').click(function() {
    swal('Função em breve disponível');
});
</script>

<script>
function enviarAnexosCotationFonecedor(cotation_id, provider_id, cont) {
    let elemento = "#cotation-reference-anexo-" + cont;
    $(elemento).val(cotation_id);
    elemento = "#provider-reference-anexo-" + cont;
    $(elemento).val(provider_id);
    elemento = "#form-anexo-uteis-fornecedor-" + cont;
    let formdata = new FormData($(elemento)[0]);
    let link = "<?= $this->Url->build(['action' => 'saveAnexosFornecedor']) ?>";
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

function validarCampos(cont) {
    let elemento = '';
    if($('#type-cotation').val() == 0){
        elemento = "#prazo-entrega" + cont;
        if ($(elemento).val() == ""){
            $(elemento).focus();
            console.log("ERRO NO CAMPO: 'Prazo de Entrega'")
            return false;
            // } else {
            //     elemento = "#cost_freight" + cont;
            //     if ($(elemento).val() == "") {
            //         $(elemento).focus();
            //         return false;
        }else{
            elemento = "#qtd-cotation-product-itens" + cont;
            for (var i = 0; i < $(elemento).val(); i++) {
                var elementoQuantity = "#quantity-"+cont+'-'+ i,
                    elementoQuote = "#quote-"+cont+'-'+i;
                    elementoLink = "#link-"+cont+'-'+i;
                    elementoAnexo = "#anexos-file-"+cont;
                    elementoAnexados = "#existe-anexados-"+cont;
                if ($(elementoQuantity).val() == "") {
                    $(elementoQuantity).focus();
                    console.log("ERRO NO CAMPO: 'Quantidade do item ("+i+")'")
                    return false;
                } else if ($(elementoQuote).val() == "") {
                    $(elementoQuote).focus();
                    console.log("ERRO NO CAMPO: 'Unitário do item ("+i+")'")
                    return false;
                } else if($(elementoAnexados).val() == "0"){
                    console.log("Valor do anexo é:" + $(elementoAnexados).val())
                    if ($(elementoAnexo).val() == "" && $(elementoLink).val() == ""){
                        console.log("ERRO NO CAMPO: 'Link do item ("+i+")'")
                        $(elementoLink).focus();
                        return false;
                    }else if (i + 1 == $(elemento).val()) {
                        return true;
                    }
                } else {
                    console.log("Valor do anexo é:" + $(elementoAnexados).val())
                    return true;
                }
            }
        }
    }else if($('#type-cotation').val() == 1){
        elemento = "#estimate" + cont;
        if ($(elemento).val() == "") {
            $(elemento).focus();
            return false;
        } else {
            elemento = "#expectation_start" + cont;
            if ($(elemento).val() == "") {
                $(elemento).focus();
                return false;
            } else {
                elemento = "#service_time" + cont;
                if ($(elemento).val() == "") {
                    $(elemento).focus();
                    return false;
                } else {
                    return true;
                }

            }

        }
    }
}

function editarDadosCotacao(cont){
    if( !validarCampos(cont) ){
        swal({
            title: "Por favor",
            text: "Para continuar, preencha todos os campos obrigatórios corretamente.",
            icon: "info",
            button: "Está bem"
        }).then(value => {
            validarCampos(cont);
        });
    }else{
        let tela = '#tela' + cont;
        var edicao;
        if($('#type-cotation').val() == 0){

            //FAZER VALIDAÇÃO DE CAMPOS VAZIOS ANTES DE EDITAR

            let elemento1 = "#cotation-provider-id" + cont;
            let elemento2 = "#cost_freight" + cont;
            let elemento3 = "#prazo-entrega" + cont;

            edicao = {
                type: $('#type-cotation').val(),
                cotation_provider_id: $(elemento1).val(),
                cost: $(elemento2).val(),
                deadline: $(elemento3).val(),
                itens: [],
            }

            let elemento = "#qtd-cotation-product-itens" + cont;
            for (let i = 0; i < $(elemento).val(); i++) {
                let elemento4 = "#cotation-product-item-id-" + cont + "-" + i;
                let elemento5 = "#quote-" + cont + "-" + i;
                let elemento6 = "#quantity-"+ cont + "-" + i;
                let elemento7 = "#link-"+ cont + "-" + i;

                edicao.itens[i] = {
                    cotation_product_item_id: $(elemento4).val(),
                    quote: $(elemento5).val(),
                    quantity: $(elemento6).val(),
                    link_item: $(elemento7).val(),
                }
            }

        }else if($('#type-cotation').val() == 1){
            let elemento1 = tela + " #estimate" + cont;
            let elemento2 = tela + " #expectation_start" + cont;
            let elemento3 = tela + " #service_time" + cont;
            let elemento4 = tela + " #cotation-service-id" + cont;

            edicao = {
                type: $('#type-cotation').val(),
                cotation_service_id: $(elemento4).val(),
                estimate: $(elemento1).val(),
                expectation_start: $(elemento2).val(),
                service_time: $(elemento3).val(),
            }
        }

        let p = $.post('<?= $this->Url->build(['action' => 'editCotationProductSend']) ?>', edicao);
        carregarLoad(true);
        p.done(function response(data) {
            carregarLoad(false);
            if (data.result == 'success') {
                let elemento = '#provider-id' + cont;
                enviarAnexosCotationFonecedor($('#id-cotation').val(), $(elemento).val(), cont);
                swal({
                    title: "Cotação Editada",
                    text: "Deseja continuar editando seus envios?",
                    icon: "success",
                    buttons: ['Não, finalizar','Sim, continuar'],
                }).then((willDelete) => {
                    if (willDelete) {
                        window.location = "<?=$this->Url->build(['prefix' => 'partner', 'controller' => 'Quotation', 'action' => 'edit', $cotation->id])?>?f="+cont;
                    } else {
                        window.location = "<?= $this->Url->build(['action' => '../sent']) ?>";
                    }
                });
            } else {
                swal("Falha ao enviar cotação.", {
                    icon: "error"
                }).then(value => {

                });
            }
        });
    }
}
</script>

<style>

.quantity::-webkit-inner-spin-button,
.quantity::-webkit-outer-spin-button {
  -webkit-appearance: none;
}

.quantity {
  -moz-appearance: textfield;
}
</style>

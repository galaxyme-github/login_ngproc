<?php $this->assign('title', 'Prévia da Cotação');

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

$menorValorOfertado = 0;
?>
<style>
    .has-feedback label{
        font-weight: bold
    }
</style>

<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.3.0/css/font-awesome.min.css">

<!-- jQuery 3 -->
<script src="<?= $this->Url->build('bower_components/jquery/dist/jquery.min.js') ?>"></script>
<!-- jQuery Mask Plugin -->
<script src="<?= $this->Url->build('bower_components/jQuery-Mask-Plugin-master/dist/jquery.mask.min.js') ?>"></script>
<!-- Sweetalert -->
<script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
<!-- jquery-maskmoney -->
<script src="<?= $this->Url->build('bower_components/plentz-jquery-maskmoney-cdbeeac/dist/jquery.maskMoney.js') ?>"></script>

<div class="dashboard-container">
    <div class="ngproc-card">
        <div class="ngproc-card-title">
            <div class="ngproc-card-title-button" style="padding-top:4px">
                <?= $this->Html->link('< Envios de Parceiros', ['action' => 'view', $cotation->main_cotation_id], ['class' => 'btn', 'id' => 'voltar']); ?>
            </div>
            <div class="ngproc-card-title-abas">
                <nav class="nav_tabs">
                    <ul id="nav_tabs_ul">
                        <!-- As abas dos fornecedores aparecerão aqui -->
                        <?php if($cotation->type == 1) : ?>
                            <?php
                            $cont = 0;
                            //$nomeFornecedor = '';
                            foreach ($cotation->cotation_service as $item) : ?>
                            <?php
                                //CLIENTE NÃO QUER QUE APAREÇA O NOME DO FORNECEDOR
                                // foreach ($cotation->cotation_providers as $ct_provider){
                                //     if($item->provider_id == $ct_provider->provider->id){
                                //         $nomeFornecedor = $ct_provider->provider->name;
                                //     }
                                // }
                            ?>
                                <li id="aba-f<?=$cont?>"><input type="radio" name="tabs" class="rd_tabs" id="tab<?=$cont?>" <?= $cont == 0 ? 'checked' : '' ?>><label class="active" for="tab<?=$cont?>" onclick="trocarDeTela(<?=$cont?>,<?=$item->provider_id?>)"><?= 'Aba '. ($cont + 1) //formatarNomeFornecedor($nomeFornecedor)?></label></li>
                                <?php $cont++?>
                            <?php endforeach; ?>
                        <?php endif; ?>

                        <?php if($cotation->type == 0) : ?>
                        <?php $arrayProvidersCotation = []; ?>
                            <?php
                            $cont = 0;
                            //$nomeFornecedor = '';
                            foreach ($cotation->cotation_providers as $k => $ct_provider): ?>
                            <?php
                            // for($i = 0; $i < count($providers); $i++){
                            //     if($providers[$i]['id'] == $ct_provider->provider->id){
                            //         $nomeFornecedor = $providers[$i]['name'];
                            //     }
                            // }
                            ?>
                                <li id="aba-f<?=$cont?>"><input type="radio" name="tabs" class="rd_tabs" id="tab<?=$cont?>" <?= $cont == 0 ? 'checked' : '' ?>><label class="active" for="tab<?=$cont?>" onclick="trocarDeTela(<?=$cont?>,0)"><?= 'Forn. '. ($cont + 1) //formatarNomeFornecedor($nomeFornecedor)?></label></li>
                                <?php $cont++?>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </ul>
                </nav>
            </div>
        </div>
        <input type="hidden" id="type-cotation" value="<?=$cotation->type?>">
            <!--
                ====================================================
                            COTAÇÃO DE SERVIÇO
                ====================================================
            -->
            <?php if($cotation->type == 1) : ?>
            <input type="hidden" id="qtd_envios_servico" value="<?=count($cotation->cotation_service)?>">
            <!-- TELA - SERVIÇO -->
            <?php $cont = 0;
            foreach ($cotation->cotation_service as $ct_service) : ?>
            <div id="tela<?=$cont?>" class="ngproc-card-content" style="padding-left:25px">
                <div class="row">
                    <div id="partner-view-service">
                        <div>
                            <div class="content-left col-sm-8">
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
                                        <input type="text" class="form-control" value="<?= $cotation->cotation_service[0]->getCategoryName() ?>" readonly="readonly">
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
                                    <div class="form-group has-feedback input-linha col-sm-6">
                                        <label>Expectativa de orçamento</label>
                                        <input type="text" class="form-control" value="<?= "R$ " . $this->Number->format($ct_service->estimate, ['places' => 2, 'locale' => 'pt_BR']) ?>" readonly="readonly">
                                    </div>
                                    <div class="form-group has-feedback input-linha col-sm-6">
                                        <label>Expectativa de início de serviço</label>
                                        <input type="text" class="form-control" value="<?= $ct_service->expectation_start; ?>" readonly="readonly">
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="form-group has-feedback input-linha col-sm-6">
                                        <label>Tempo estimado de demanda do serviço</label>
                                        <input type="text" class="form-control" value="<?= $ct_service->service_time; ?>" readonly="readonly">
                                    </div>
                                </div>
                            </div>
                            <div class="content-right  col-sm-4">
                                <div class="title-left h5 col-sm-12">
                                    Arquivos anexados
                                </div>
                                <!-- <?php foreach ($cotation->cotation_attachments as $anexo) : ?>
                                    <div class="col-sm-12">
                                        <a href="#"><?= $anexo->name_original ?></a>
                                    </div>
                                <?php endforeach; ?> -->
                            </div>
                        </div>
                    </div>
                </div>
                   <!-- <script>
                    //Essa função é para verificar se o cliente tem desconto ou não
                    //dependendo do resultado, ele exibe o informativo e/ou redireciona
                    //para efetuar o pagamento.
                    function acceptCotation() {
                        $("#btn-open-modal-cotation").click();
                    }
                </script> -->
                <div class="row btn-partner-view-cot">
                    <!-- <button type="button" class="btn btn-success" id="btn-participar" style="padding-left:50px;padding-right:50px;margin-right: 15px;">Aceitar</button>
                    <a style="
                            padding-left: 55px;
                            padding-right: 55px;
                            color: white;
                            border-radius: 4px;
                            margin-right: 15px
                        " href="<?= $this->Url->build(['action' => 'cancel-cotation', $cotation->main_cotation_id]) ?>" class="btn btn-danger" onclick="return confirm('Você tem certeza que deseja cancelar sua cotação?')" >
                    Cancelar</a> -->
                    <div>
                        <?php if($cotation->getVerifyPayedCotation()): ?>
                            <a  class="btn btn-link" href="<?= $this->Url->build(['action' => 'view-details', $cotation->id]) ?>">
                                Visualizar
                            </a>
                        <?php else: ?>
                            <?php

                            $orcamento = $cotation->providers[0]->cotation_service[0]->estimate;


                             if($menorValorOfertado > $orcamento) $menorValorOfertado = $orcamento;
                                //Calculando o valor que o cliente deve pagar
                                if($menorValorOfertado > $expecCliente) {
                                    $pay = $cotation->getPercentViewCotationParams($menorValorOfertado);
                                }else{
                                    $pay = $cotation->getPercentViewCotationParams($expecCliente);
                                }

                            ?>
                            <button
                            style="
                            padding-left: 55px;
                            padding-right: 55px;
                            color: white;
                            border-radius: 4px;
                            margin-right: 15px;"
                            type="button"
                            class="btn btn-success"
                            id="btn-participar"
                            onclick="applyDiscount(<?= $cotation->id ?>, <?= $pay ?>, <?=$cotation->main_cotation_id?>)">
                            Aceitar</button>

                            <a style="
                                padding-left: 55px;
                                padding-right: 55px;
                                color: white;
                                border-radius: 4px;
                                margin-right: 15px;"
                                href=""
                                class="btn btn-danger"
                                id="btn-rejeitar">
                            Rejeitar</a>
                            <?= $this->Html->link('Voltar', ['action' => 'view', $cotation->main_cotation_id], ['class' => 'btn btn-dark', 'id' => 'preto']); ?>
                        <?php endif; ?>
                    </div>

                </div>
                <div class="error"></div>
                <!-- <div class="row btn-partner-view-cot">
                <div>
                        <?php if($cotation->getVerifyPayedCotation()){ ?>
                            <a  class="btn btn-link" href="<?= $this->Url->build(['action' => 'view-details', $cotation->id]) ?>">
                                Visualizar
                            </a>
                        <?php }else{ ?>
                            <button
                            style="
                            padding-left: 55px;
                            padding-right: 55px;
                            color: white;
                            border-radius: 4px;
                            margin-right: 15px;"
                            type="button"
                            class="btn btn-success"
                            id="btn-participar"
                            data-toggle="modal"
                            data-target="#modal_<?= $cotation->id ?>">
                            Aceitar</button>

                            <a style="
                                padding-left: 55px;
                                padding-right: 55px;
                                color: white;
                                border-radius: 4px;
                                margin-right: 15px;"
                                href=""
                                class="btn btn-danger"
                                id="btn-rejeitar">
                            Rejeitar</a>
                            <?= $this->Html->link('Voltar', ['action' => 'view', $cotation->main_cotation_id], ['class' => 'btn btn-dark', 'id' => 'preto']); ?>
                        <?php } ?>
                        <div class="modal fade" id="modal_<?= $cotation->id ?>" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                            <div class="modal-dialog" role="document">
                                <div class="modal-content">
                                <div class="modal-body">
                                <h5 class="modal-title" id="exampleModalLabel">Atenção</h5>
                                    <p style="margin: 30px 100px;">
                                    Ao aceitar receber os dados dessa cotação, você concorda com o pagamento no valor de <b><?="R$" . $this->Number->format($cotation->getPercentViewCotation(), ['places' => 2, 'locale' => 'pt_BR'])?>.</b>
                                    </p>
                                    <h5 style="text-align: center;">
                                        <button type="button" class="btn btn-primary" data-dismiss="modal" data-toggle="modal" data-target="#modal_payment<?= $cotation->id ?>">Prosseguir</button>
                                    </h5>
                                    <h5 style="text-align: center;">
                                        <button type="button" class="btn btn-link" data-dismiss="modal">Fechar</button>
                                    </h5>
                                </div>
                                </div>
                            </div>
                        </div>

                        <div class="modal fade" id="modal_payment<?= $cotation->id ?>" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                            <div class="modal-dialog" role="document">
                                <div class="modal-content">
                                <div class="modal-body">
                                <h5 class="modal-title" id="exampleModalLabel">Pagamento</h5>
                                    <p style="margin: 30px 100px;">
                                    <input type="radio" checked />  <img src="/img/paypal.jpg" />
                                    </p>
                                    <h5 style="text-align: center;">
                                        <button type="button" class="btn btn-primary" onclick="window.location.href='<?= $this->Url->build(['action' => 'createPaymentPaypal', $cotation->id])?>'">Prosseguir</button>
                                    </h5>
                                </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div> -->
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
            <?php $cont = 0;
            foreach ($cotation->cotation_providers as $k => $ct_provider): ?>
            <div id="tela<?=$cont?>" class="ngproc-card-content" style="padding-left:25px">
                <div class="row">
                    <div id="partner-view-product">
                        <div>
                            <div class="content-left col-sm-8">
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
                                        <input type="text" class="form-control" value="<?= $cotation->provider_qtd ?>" readonly="readonly">
                                    </div>
                                    <div class="form-group has-feedback input-linha col-sm-6">
                                        <label>Abrangência da cotação</label>
                                        <input type="text" class="form-control" value="<?= $cotation->coverage; ?>" readonly="readonly">
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="form-group has-feedback input-linha col-sm-6">
                                        <label>Orçamento Enviado</label>
                                        <input type="text" class="form-control" value="<?php
                                            if ($cotation->type == 0) {
                                                $orcamento = 0;
                                                foreach ($cotation->cotation_product->cotation_product_items as $item) {
                                                    if($item->provider_id == $ct_provider->provider_id){
                                                        $orcamento += ($item->quote * $item->quantity);
                                                    }
                                                }
                                                if($menorValorOfertado == 0){
                                                    $menorValorOfertado = $orcamento;
                                                }else if($menorValorOfertado > $orcamento) $menorValorOfertado = $orcamento;
                                                echo 'R$ ' . $this->Number->format($orcamento, ['places' => 2, 'locale' => 'pt_BR']);
                                            } ?>" readonly="readonly">
                                    </div>
                                    <div class="form-group has-feedback input-linha col-sm-6">
                                        <label>Orçamento Esperado</label>
                                        <input type="text" class="form-control" value="<?php
                                            if ($cotation->type == 0) {
                                                echo 'R$ ' . $this->Number->format($expecCliente, ['places' => 2, 'locale' => 'pt_BR']);
                                            } ?>" readonly="readonly">
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="form-group has-feedback input-linha col-sm-6">
                                        <label >Recebido em:</label>
                                        <input type="text" class="form-control" value="<?= date("d/m/Y - H:i", strtotime($cotation->created)); ?>" readonly="readonly">
                                    </div>
                                    <div class="form-group has-feedback input-linha col-sm-6">
                                        <label >Prazo para conclusão</label>
                                        <input type="text" class="form-control" value="<?= $cotation->deadline_date; ?>" readonly="readonly">
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="form-group has-feedback input-linha col-sm-6">
                                        <label for="prazo-entrega">Prazo de entrega ()</label>
                                        <input type="number" class="form-control" id="prazo-entrega"  min="1" style="padding-right: 5px" value="<?=$ct_provider->deadline?>" readonly="readonly">
                                    </div>
                                    <div class="form-group has-feedback input-linha col-sm-6">
                                        <label for="cost_freight">Valor do frete</label>
                                        <input type="text" class="form-control dinheiro-real" id="cost_freight" value="<?=$ct_provider->cost <= 0 ? 'Grátis' : 'R$ ' . $this->Number->format($ct_provider->cost, ['places' => 2, 'locale' => 'pt_BR'])?>" readonly="readonly">
                                    </div>
                                </div>
                            </div>
                            <!-- <div class="content-right float-right col-sm-4">
                                <div class="title-left h5 col-sm-12">
                                    Arquivos anexados
                                </div>
                                < !-- <?php foreach ($cotation->cotation_attachments as $anexo) : ?>
                                    <div class="col-sm-12">
                                        <a href="<?= $this->Url->build("/uploads/cotations/{$anexo->name}") ?>" download="<?= $anexo->name_original ?>"><?= $anexo->name_original ?></a>
                                    </div>
                                <?php endforeach; ?> -- >
                            </div> -->
                        </div>
                        <div id="tabela-cotacoes" class="table-responsive" style="margin-left: 15px;">
                            <table id="cotacoes" class="table table-bordered table-responsive">
                                <div class="scrollmenu">
                                    <thead>
                                        <tr>
                                            <th>Nome</th>
                                            <th>Categoria</th>
                                            <th>Quantidade</th>
                                            <th>Orçamento Unitário</th>
                                            <th>Fabricante</th>
                                            <th>Modelo</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($cotation->cotation_product->cotation_product_items as $item) : ?>
                                        <?php if($item->provider_id == $ct_provider->provider_id): ?>
                                            <tr>
                                                <td><?= $item->product->item_name ?></td>
                                                <td style="text-align:center"><?= $item->product->getCategoryName() ?></td>
                                                <td style="text-align:center" class="lp-s-1"><?= $item->quantity ?></td>
                                                <td style="text-align:center"><?= "R$ " . $this->Number->format($item->quote, ['places' => 2, 'locale' => 'pt_BR']); ?></td>
                                                <td style="text-align:center"><?= $item->product->manufacturer ?></td>
                                                <td style="text-align:center"><?= $item->product->model ?></td>
                                                <!-- <td style="text-align:center"><?= $item->product->sku ?></td> -->
                                            </tr>
                                        <?php endif; ?>
                                        <?php endforeach; ?>
                                    </tbody>
                                </div>
                            </table>
                        </div>
                    </div>
                </div>
                <!-- <script>
                    //Essa função é para verificar se o cliente tem desconto ou não
                    //dependendo do resultado, ele exibe o informativo e/ou redireciona
                    //para efetuar o pagamento.
                    function acceptCotation() {
                        $("#btn-open-modal-cotation").click();
                    }
                </script> -->
                <div class="row btn-partner-view-cot">
                    <!-- <button type="button" class="btn btn-success" id="btn-participar" style="padding-left:50px;padding-right:50px;margin-right: 15px;">Aceitar</button>
                    <a style="
                            padding-left: 55px;
                            padding-right: 55px;
                            color: white;
                            border-radius: 4px;
                            margin-right: 15px
                        " href="<?= $this->Url->build(['action' => 'cancel-cotation', $cotation->main_cotation_id]) ?>" class="btn btn-danger" onclick="return confirm('Você tem certeza que deseja cancelar sua cotação?')" >
                    Cancelar</a> -->
                    <div>
                        <?php if($cotation->getVerifyPayedCotation()): ?>
                            <a  class="btn btn-link" href="<?= $this->Url->build(['action' => 'view-details', $cotation->id]) ?>">
                                Visualizar
                            </a>
                        <?php else: ?>
                            <?php
                                //Calculando o valor que o cliente deve pagar
                                if($menorValorOfertado > $expecCliente) {
                                    $pay = $cotation->getPercentViewCotationParams($menorValorOfertado);
                                }else{
                                    $pay = $cotation->getPercentViewCotationParams($expecCliente);
                                }
                                //dump($pay);
                            ?>
                            <button
                            style="
                            padding-left: 55px;
                            padding-right: 55px;
                            color: white;
                            border-radius: 4px;
                            margin-right: 15px;"
                            type="button"
                            class="btn btn-success"
                            id="btn-participar"
                            onclick="applyDiscount(<?= $cotation->id ?>, <?= $pay ?>, <?=$cotation->main_cotation_id?>)">
                            Aceitar</button>

                            <a style="
                                padding-left: 55px;
                                padding-right: 55px;
                                color: white;
                                border-radius: 4px;
                                margin-right: 15px;"
                                href=""
                                class="btn btn-danger"
                                id="btn-rejeitar">
                            Rejeitar</a>
                            <?= $this->Html->link('Voltar', ['action' => 'view', $cotation->main_cotation_id], ['class' => 'btn btn-dark', 'id' => 'preto']); ?>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
            <?php $cont++; ?>
            <?php endforeach; ?>
            <?php endif;?>
        </div>
        <div class="error"></div>
    </div>
</div>
<script>
    function enviaPagseguro(id, valor, mainId){
        carregarLoad(true);
        let body = {};
        body['id'] = id;
        body['mainId'] = mainId;

        let element = "#discountedValue" + id;
        body['valor'] = valor ? parseFloat(valor).toFixed(2) : parseFloat($(element).val()).toFixed(2);
        console.log("PagSeguro:", body);
        let p = $.post('<?= $this->Url->build(['action' => 'createPaymentPagSeguro']) ?>', body);
        console.log(p['responseText']);
        p.done(function response(data) {
            console.log(data);
            carregarLoad(false);
            if (data.result == 'success') {
                $('.btn-fechar-modal-opc-pag').click();
                //$('#code').val(data.code[0]);
                // setTimeout(() => {
                    //$('#comprar').submit();

                    window.location.href = 'https://pagseguro.uol.com.br/checkout/v2/payment.html?code='+data.code[0];
                    // window.location.href = 'https://sandbox.pagseguro.uol.com.br/checkout/v2/payment.html?code='+data.code[0];

                // }, 1000);
                //console.log(data.code[0]);
            } else {
                //$(".error").html(data);
                swal("Falha na conexão com o PagSeguro.", {
                    icon: "error"
                }).then(value => {

                });
            }
        });
    }
</script>
<script>
    function applyDiscount(id, valor, mainId){
        let body = {};
        body['id'] = id;
        body['valor'] = parseFloat(valor).toFixed(2);
        body['mainId'] = mainId;
        let p = $.post('<?= $this->Url->build(['action' => 'isPurchasesUser']) ?>', body);
        p.done(function response(data){
            let free = false
            if(data.result == "success" && data.discount){
                let msg = '';
                if(data.discountedValue == 0){
                    msg = `Essa cotação será GRATUITA!`;
                    free = true;
                }else{
                    body['valor'] = data.discountedValue;
                    msg = `De ${valor.toLocaleString('pt-BR', { style: 'currency', currency: 'BRL' })} você irá pagar apenas ${data.discountedValue.toLocaleString('pt-BR', { style: 'currency', currency: 'BRL' })}.
                    Ao aceitar receber os dados dessa cotação, você concorda com o pagamento deste no valor.`;
                }
                swal({
                    title:"Ganhou desconto!",
                    text:`Identificamos que essa é sua primeira compra, e por isso aplicamos um desconto.

                    ${msg}`,
                }).then( value =>{
                    $(".btn-link-close-modal").click();
                    if(free){
                        carregarLoad(true);
                        body['valor'] = 0;
                        let np = $.post('<?= $this->Url->build(['action' => 'applyDiscount']) ?>', body);
                        np.done(function response(data){
                            if(data.result == "success"){
                                window.location.href = `/client/relatorios/details/${body['id']}`;
                            }
                            carregarLoad(false);
                        })
                    }else{
                        let element = "#discountedValue" + id;
                        $(element).val(body['valor'])
                        $(".btn-open-modal-discount").click();
                    }
                });
            }else if(data.result == "success" && !data.discount){
                // $('.btn-open-modal').click();
                $("#btn-open-modal-cotation").click();
            }
        })
    }
</script>

<!-- MODAL QUE INFORMA O VALOR A SER PAGO PELO CLIENTE -->
<button
    id="btn-open-modal-cotation"
    style="display:none"
    data-toggle="modal"
    data-target="#modal_<?= $cotation->id ?>"></button>
<div class="modal fade" id="modal_<?= $cotation->id ?>" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
        <div class="modal-body">
        <h5 class="modal-title" id="exampleModalLabel">Atenção</h5>
            <p style="margin: 30px 100px;">
            Ao aceitar receber os dados dessa cotação, você concorda com o pagamento no valor de
                <b><?php
                    echo "R$ " . $this->Number->format($pay, ['places' => 2,'precision' => 2, 'locale' => 'pt_BR']);
                ?></b>.
            </p>
            <h5 style="text-align: center;">
                <button type="button" class="btn btn-primary btn-open-modal" data-dismiss="modal" data-toggle="modal" data-target="#modal_payment<?= $cotation->id ?>">Prosseguir</button>
                <button style="display:none" class="btn-open-modal-discount" data-toggle="modal" data-target="#modal_discount<?= $cotation->id ?>"></button>
                <!-- <button type="button" class="btn btn-primary">Prosseguir</button> -->
            </h5>
            <h5 style="text-align: center;">
                <button type="button" class="btn btn-link btn-link-close-modal" data-dismiss="modal">Fechar</button>
            </h5>
        </div>
        </div>
    </div>
</div>

<!-- MODAL QUE EXIBE OPÇÕES DE PAGAMENTO PARA DESCONTO -->
<div class="modal fade" id="modal_discount<?= $cotation->id ?>" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
        <div class="modal-body">
        <h5 class="modal-title" id="exampleModalLabel">Forma de Pagamento</h5>
            <p style="margin: 30px 100px;">
            <input type="radio" checked />  <img src="/img/pagseguro.jpg" width="220" height="44" />
            </p>
            <h5 style="text-align: center;">
                <input type="hidden" id="discountedValue<?=$cotation->id?>">
                <button type="button" class="btn btn-primary" onclick="enviaPagseguro(<?= $cotation->id ?>, null, <?=$cotation->main_cotation_id?>)">Prosseguir</button>
            </h5>
            <h5 style="text-align: center;">
                <button style="display:none" type="button" class="btn btn-link btn-fechar-modal-opc-pag" data-dismiss="modal">Fechar</button>
            </h5>
        </div>
        </div>
    </div>
</div>

<!-- Script PagSeguro -->
<div class="modal fade" id="modal_payment<?= $cotation->id ?>" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
        <div class="modal-body">
        <h5 class="modal-title" id="exampleModalLabel">Forma de Pagamento</h5>
            <p style="margin: 30px 100px;">
            <input type="radio" checked />  <img src="/img/pagseguro.jpg" width="220" height="44" />
            </p>
            <h5 style="text-align: center;">
                <!-- <button type="button" class="btn btn-primary" onclick="window.location.href='<?= $this->Url->build(['action' => 'createPaymentPaypal', $cotation->id])?>'">Prosseguir</button> -->
                <!-- <button type="button" class="btn btn-primary" onclick="window.location.href='<?= $this->Url->build(['action' => 'createPaymentPagSeguro', '?' => ['id' => $cotation->id, 'valor' => $pay, 'mainId' => $cotation['main_cotation_id']]])?>'">Prosseguir</button> -->
                <button type="button" class="btn btn-primary" onclick="enviaPagseguro(<?= $cotation->id ?>, <?= $pay ?>, <?=$cotation->main_cotation_id?>)">Prosseguir</button>
            </h5>
            <h5 style="text-align: center;">
                <button style="display:none" type="button" class="btn btn-link btn-fechar-modal-opc-pag" data-dismiss="modal">Fechar</button>
            </h5>
        </div>
        </div>
    </div>
</div>

<!-- Form Lightbox PagSeguro -->
<form id="comprar" action="https://pagseguro.uol.com.br/checkout/v2/payment.html" method="post" onsubmit="PagSeguroLightbox(this); return false;">
    <input type="hidden" name="code" id="code" value="" />
</form>
<!-- Form Lightbox PagSeguro -->
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

<script>
function trocarDeTela(tela, idCotation){
    if($('#type-cotation').val() == 0){
        let element = '#tela' + tela;
        $(element).show();
        for (let i = 0; i < $('#qtd-envios-produtos').val(); i++) {
            element = '#tela' + i;
            if(i != tela){
                $(element).hide();
            }
        }

    }else if($('#type-cotation').val() == 1){
        let element = '#tela' + tela;
        $(element).show();
        for (let i = 0; i < $('#qtd_envios_servico').val(); i++) {
            element = '#tela' + i;
            if(i != tela){
                $(element).hide();
            }
        }
    }
}
</script>

<script>
$('#btn-rejeitar').click(function(e){
    e.preventDefault();
    swal({
    title: "Tem certeza?",
    text: "Você está prestes a rejeitar o envio do parceiro (De todos os fornecedores).",
    icon: "warning",
    buttons: ['Voltar','Sim, rejeitar'],
    dangerMode: true,
    })
    .then((willDelete) => {
    if (willDelete) {
        window.location = "<?= $this->Url->build(['action' => 'reject-cotation', $cotation->id]) ?>";
    } else {

    }
    });
});
</script>

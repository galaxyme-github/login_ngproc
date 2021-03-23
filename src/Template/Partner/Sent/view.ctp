<?php $this->assign('title', 'Cotação Enviada');

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

function encurtarLink($link){
    /**
     * TRATA APENAS ESSES CASOS
     * http://www.ngproc.com.br      // ngproc
     * https://www.ngproc.com.br";   // ngproc
     * ngproc.com.br;                // ngproc
     * www.ngproc.com.br;            // ngproc
     */
    $link = strtolower($link);
    $start = 0;
    $new_link = substr($link,$start,7);
    if($new_link === 'http://'){
    	$start = 7;
        $new_link = substr($link,$start);
    }else{
        $new_link = substr($link,0,8);
    	if($new_link === 'https://'){
    		$start = 8;
    		$new_link = substr($link,$start);
    	}
    }

    if($start == 7 || $start == 8){
    	$arr = str_split($new_link);

	    if($arr[0] == 'w' && $arr[1] == 'w' && $arr[2] == 'w' ){
	    	$start = 4;
	    	$new_link = substr($new_link,$start);
	    	$arr = str_split($new_link);
	    }

	    $new_link = '';
	    foreach ($arr as $k => $value) {
	        if($value != '.'){
	            $new_link = $new_link . $value;
	        }else{
	            break;
	        }
	    }

    }else{

    	$arr = str_split($link);

    	if($arr[0] == 'w' && $arr[1] == 'w' && $arr[2] == 'w' ){
	    	$start = 4;
	    }

	    $new_link = substr($link,$start);
    	$arr = str_split($new_link);

	    $new_link = '';
	    foreach ($arr as $k => $value) {
	        if($value != '.'){
	            $new_link = $new_link . $value;
	        }else{
	            break;
	        }
	    }
    }

    return $new_link;

}

$menorValorOfertado = 0;
?>
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.3.0/css/font-awesome.min.css">

<div class="dashboard-container">
    <div class="ngproc-card">
        <div class="ngproc-card-title cards-abas">
            <div class="ngproc-card-title-button" style="padding-top:4px">
                <?= $this->Html->link('< Meus Envios', ['action' => 'index'], ['class' => 'btn', 'id' => 'voltar']); ?>
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
                                <li id="aba-f<?=$cont?>"><input type="radio" name="tabs" class="rd_tabs" id="tab<?=$cont?>" <?= $cont == 0 ? 'checked' : '' ?>><label class="active" for="tab<?=$cont?>" onclick="trocarDeTela(<?=$cont?>,<?=$item->provider_id?>)"><?= formatarNomeFornecedor($nomeFornecedor) //'Aba '. ($cont + 1)?></label></li>
                                <?php $cont++?>
                            <?php endforeach; ?>
                        <?php endif; ?>

                        <?php if($cotation->type == 0) : ?>
                        <?php $arrayProvidersCotation = []; ?>
                            <?php
                            $cont = 0;
                            $nomeFornecedor = '';
                            foreach ($cotation->cotation_providers as $k => $ct_provider): ?>
                            <?php
                            for($i = 0; $i < count($providers); $i++){
                                if($providers[$i]['id'] == $ct_provider->provider->id){
                                    $nomeFornecedor = $providers[$i]['name'];
                                }

                            }
                            ?>
                                <li id="aba-f<?=$cont?>"><input type="radio" name="tabs" class="rd_tabs" id="tab<?=$cont?>" <?= $cont == 0 ? 'checked' : '' ?>><label class="active" for="tab<?=$cont?>" onclick="trocarDeTela(<?=$cont?>,0)"><?= formatarNomeFornecedor($nomeFornecedor)//'Aba '. ($cont + 1)?></label></li>
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
                                        <input type="text" class="form-control" value="" readonly="readonly">
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
                                <?php foreach ($cotation->cotation_attachments as $anexo) : ?>
                                    <div class="col-sm-12">
                                        <a href="#"><?= $anexo->name_original ?></a>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row btn-partner-view-cot">
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
                                                        $orcamento = $orcamento + ($item->quote * $item->quantity);
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
                                        <label >Prazo para conclusão</label>
                                        <input type="text" class="form-control" value="<?= $cotation->deadline_date; ?>" readonly="readonly">
                                    </div>
                                    <div class="form-group has-feedback input-linha col-sm-6">
                                        <label for="prazo-entrega">Prazo de entrega (dias)</label>
                                        <input type="number" class="form-control" id="prazo-entrega"  min="1" style="padding-right: 5px" value="<?=$ct_provider->deadline?>" readonly="readonly">
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="form-group has-feedback input-linha col-sm-6">
                                        <label for="cost_freight">Valor do frete</label>
                                        <input type="text" class="form-control dinheiro-real" id="cost_freight" value="<?='R$ ' . $this->Number->format($ct_provider->cost, ['places' => 2, 'locale' => 'pt_BR'])?>" readonly="readonly">
                                    </div>
                                </div>
                            </div>
                            <div class="content-right float-right col-sm-4">
                                <div class="title-left h5 col-sm-12">
                                    Arquivos anexados
                                </div>
                                <?php $issetAnexos = false;
                                 foreach ($cotation->cotation_attachments as $anexo) : ?>
                                    <?php if ($anexo['provider_id'] == $ct_provider['provider_id']) : ?>
                                    <?php $issetAnexos = true; ?>
                                    <div class="col-sm-12">
                                        <a href="<?= $this->Url->build("/uploads/cotations/{$anexo->name}") ?>" download="<?= $anexo->name_original ?>">
                                            <img src="<?= $this->Url->build('/img/icons/download.png')?>" alt="Download" width="14" height="14">
                                            <span style="color:#004268"><?= $anexo->name_original ?></span>
                                        </a>
                                    </div>
                                    <?php endif; ?>
                                <?php endforeach; ?>
                                <?php if(!$issetAnexos) : ?>
                                    <span>Nenhum anexo encontrado</span>
                                <?php endif;?>
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
                                            <th>Orçamento Unitário</th>
                                            <th>Link</th>
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
                                                <td style="text-align:center"><a href="<?= $item->link_item ?>" target="_blank"><?= encurtarLink($item->link_item) ?></a></td>
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
            </div>
            <?php $cont++; ?>
            <?php endforeach; ?>
            <?php endif;?>
            <div class="row btn-partner-view-cot" style="background: #fff; margin:0; padding:30px">
                    <?php if($cotation->status != 5) :?>
                    <input
                        value="Quero Editar"
                        type="button"
                        class="btn btn-primary"
                        id="verde"
                        style="margin-right: 15px;"
                        onclick="window.location = '<?=$this->Url->build(['prefix' => 'partner', 'controller' => 'Quotation', 'action' => 'edit', $cotation->id])?>'">
                    <?php endif; ?>
                    <!-- <input type="button" class="btn btn-dark" id="preto" value="Voltar" style="margin-right: 15px;"> -->
                    <?= $this->Html->link('Voltar', ['action' => '../sent'], ['class' => 'btn btn-dark', 'id' => 'preto']); ?>
                </div>
        </div>
        <div class="error"></div>
    </div>
</div>
<!-- jQuery 3 -->
<script src="<?= $this->Url->build('bower_components/jquery/dist/jquery.min.js') ?>"></script>
<!-- jQuery Mask Plugin -->
<script src="<?= $this->Url->build('bower_components/jQuery-Mask-Plugin-master/dist/jquery.mask.min.js') ?>"></script>
<!-- Sweetalert -->
<script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
<!-- jquery-maskmoney -->
<script src="<?= $this->Url->build('bower_components/plentz-jquery-maskmoney-cdbeeac/dist/jquery.maskMoney.js') ?>"></script>

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

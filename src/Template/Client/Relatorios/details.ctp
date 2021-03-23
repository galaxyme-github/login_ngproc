<?php
if($purchase->status == 3 || $purchase->status == 4 ||$purchase->status == 8){
    $this->assign('title', 'Detalhes do Pagamento');
}else{
    $this->assign('title', 'Detalhes da Transação');
}
function formatarNomeFornecedor($texto){
    //Entrada Google LTDA
    //Retorno: G. LTDA
    $nameArray = preg_split('" "', strtoupper($texto));

    $name = count($nameArray) > 1 ? $nameArray[1] : $nameArray[0];
    $fornecedorName = $name;
    if(count($nameArray) > 1){
        $lastName = str_split($nameArray[0]);
        $fornecedorName = $lastName[0] . "." . " " .$fornecedorName  ;
    }
    return $fornecedorName;
}

function formatName($texto){
    //Entrada Google LTDA
    //Retorno: Google L.
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

?>
<!-- Script PagSeguro -->
<script>
function conectarPagseguro(id, valor, mainId){
    let body = {};
    body['id'] = id;
    body['valor'] = parseFloat(valor).toFixed(2);
    body['mainId'] = mainId;
    //console.log(body);
    let p = $.post('<?= $this->Url->build(['action' => 'createPaymentPagSeguro']) ?>', body);
    carregarLoad(true);
    p.done(function response(data) {
        carregarLoad(false);
        if (data.result == 'success') {
            $('.btn-fechar-modal-opc-pag').click();
                window.location.href = 'https://pagseguro.uol.com.br/checkout/v2/payment.html?code='+data.code[0];
        } else {
            swal("Falha na conexão com o PagSeguro.", {
                icon: "error"
            }).then(value => {

            });
        }
    });
}

function enviaPagseguro(id, valor, mainId, code){
    let body = {};
    body['code'] = code;
    let p = $.post('<?= $this->Url->build(['action' => 'deletePurchaseForCodeTransaction']) ?>', body);
    p.done(function response(data) {
        if (data.result == 'success') {
            conectarPagseguro(id, valor, mainId);
        }else{
            swal("Falha ao tentar nova transação com o PagSeguro.", {
                icon: "error"
            });
        }
    });
}
</script>
<?= $this->Html->css("font-awesome.min"); ?>

<input type="hidden" id="type-cotation" value="<?=$cotation->type?>">
<input type="hidden" id="qtd-envios" value="<?=count($cotation->cotation_providers)?>">
<div class="dashboard-container">
    <div class="ngproc-card">
        <div class="ngproc-card-title">
            <!-- <?= $this->Html->link("< {$main_cotation->title}", ['action' => 'index'], ['class' => 'btn', 'id' => 'voltar']); ?> -->
            <?= $this->Html->link("< Relatório", ['action' => 'index'], ['class' => 'btn', 'id' => 'voltar']); ?>
        </div>
        <?php if($purchase->status == 3 || $purchase->status == 4 ||$purchase->status == 8) : ?>
        <!-- ========================================
                TELA DE COTAÇÃO PAGA
        ======================================== -->
        <div class="ngproc-card-content">
            <div class="row" style="margin-top: 2vh">

                <div class="col-xs-12 col-md-12 table-responsive">
                    <div class="col-md-8" style="margin-bottom:10px">
                        <label for="">Código do Pagamento:</label>
                        <p><?php
                            if($purchase->discounted && $purchase->value == 0){
                                echo "Nenhum";
                            }else if(!empty($purchase->code_transaction) ){
                                echo $purchase->code_transaction;
                            }else{ echo 'Pagamento não efetuado'; }
                        ?></p>
                    </div>
                    <div class="col-md-4" style="margin-bottom:10px">
                    <label for="">Opção de Pagamento:</label>
                        <p><?= $purchase->discounted && $purchase->value == 0 ? "Desconto da NGProc" : "PagSeguro" ?></p>
                    </div>
                    <div class="col-md-4">
                        <label for="">Data do Pagamento:</label>
                        <p><?= $purchase->payment_date ?></p>
                    </div>
                    <div class="col-md-4">
                        <label for="">Valor Pago:</label>
                        <p><?= "R$ " . $this->Number->format($purchase->value, ['places' => 2, 'locale' => 'pt_BR']); ?></p>
                    </div>
                    <div class="col-md-4">
                        <label for="">Status:</label>
                        <p><?= 'Paga' ?></p>
                    </div>
                    <div class="col-md-12" style="margin:30px 0 15px 0">
                        <label for="" style="text-transform:uppercase;">Detalhes da Cotação</label>
                    </div>
                    <div class="col-md-12">
                        <label for="">Parceiro:</label>
                        <p><?= formatarNomeFornecedor($cotation->user->name) ?></p>
                    </div>
                </div>
            </div>
            <div class="row box-provider" style="margin-bottom:30px">
                <div class="col-md-12 detalhes-fornecedores-abas">
                    <nav class="nav_tabs nav_tabs_inv">
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
                                    <li id="aba-f<?=$cont?>"><input type="radio" name="tabs" class="rd_tabs" id="tab<?=$cont?>" <?= $cont == 0 ? 'checked' : '' ?>><label class="active" for="tab<?=$cont?>" onclick="trocarDeTela(<?=$cont?>)"><?=formatName($nomeFornecedor)?></label></li>
                                    <?php $cont++?>
                                <?php endforeach; ?>
                            <?php endif; ?>

                            <?php if($cotation->type == 0) : ?>
                            <?php $arrayProvidersCotation = []; ?>
                                <?php
                                $cont = 0;
                                //$nomeFornecedor = '';
                                foreach ($cotation->cotation_providers as $k => $ct_provider): ?>

                                    <li id="aba-f<?=$cont?>"><input type="radio" name="tabs" class="rd_tabs" id="tab<?=$cont?>" <?= $cont == 0 ? 'checked' : '' ?>><label class="active" for="tab<?=$cont?>" onclick="trocarDeTela(<?=$cont?>)"><?= formatarNomeFornecedor($ct_provider->provider->name)?></label></li>
                                    <?php $cont++?>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </ul>
                    </nav>
                </div>
                <?php
                $cont = 0;
                foreach ($cotation->cotation_providers as $ct_provider) : ?>
                <div class="col-md-11 dados-fornecedor" id="dados-fornecedor-<?=$cont?>">


                    <div
                        style="width:100%; text-align:right">
                        <a
                            class="btn btn-primary"
                            href="https://ngproc.com.br/report/index.php?cidp=<?=$cotation->id?>&pid=<?=$ct_provider->provider_id?>&ucid=<?=$ct_provider->user_id?>"
                            target="_blank">Gerar PDF</a>
                    </div>

                    <div class="col-md-4" style="margin-top:10px">
                        <label for="">Valor do Frete:</label>
                        <?php if($ct_provider->cost == 0) : ?>
                        <p>Grátis</p>
                        <?php else : ?>
                        <p><?= "R$ " . $this->Number->format($ct_provider->cost, ['places' => 2, 'locale' => 'pt_BR']); ?></p>
                        <?php endif;?>
                    </div>
                    <div class="col-md-8" style="margin-top:10px">
                        <label for="">Prazo de Entrega (Dias):</label>
                        <p><?php
                            if($ct_provider->deadline < 1){
                                echo "Imediata";
                            }else if($ct_provider->deadline == 1){
                                echo $ct_provider->deadline . ' dia';
                            }else{
                                echo $ct_provider->deadline . ' dias';
                            }
                        ?></p>
                    </div>
                    <div class="col-md-12" style="margin: 25px 0 20px 0">
                        <label for="" style="text-transform:uppercase;">Dados do Fornecedor</label>
                    </div>
                    <div class="col-md-4">
                        <label for="">CNPJ:</label>
                        <p><?= $ct_provider->provider->cnpj ?></p>
                    </div>
                    <div class="col-md-5">
                        <label for="">Razão social ou Nome fantasia:</label>
                        <p><?= $ct_provider->provider->name ?></p>
                    </div>
                    <div class="col-md-3">
                        <label for="">Site:</label>
                        <p><a href="<?= $ct_provider->provider->site ?>" target="_blank"><?= encurtarLink($ct_provider->provider->site) ?></a></p>
                    </div>
                    <div class="col-md-12">
                        <label for="">Telefone:</label>
                        <p><?= empty($ct_provider->provider->telephone) ? '---' : $ct_provider->provider->telephone ?></p>
                    </div>
                    <div class="col-md-12" style="margin: 25px 0 20px 0">
                        <label for="" style="text-transform:uppercase;">Endereço</label>
                    </div>
                    <div class="col-md-4">
                        <label for="">CEP:</label>
                        <p><?= $ct_provider->provider->address_zipcode ?></p>
                    </div>
                    <div class="col-md-5">
                        <label for="">Rua:</label>
                        <p><?= $ct_provider->provider->address_street ?></p>
                    </div>
                    <div class="col-md-3">
                        <label for="">Número:</label>
                        <p><?= $ct_provider->provider->address_number ?></p>
                    </div>
                    <div class="col-md-4">
                        <label for="">Bairro:</label>
                        <p><?= $ct_provider->provider->address_neighborhood ?></p>
                    </div>
                    <div class="col-md-5">
                        <label for="">Cidade:</label>
                        <p><?= $ct_provider->provider->address_city ?></p>
                    </div>
                    <div class="col-md-3">
                        <label for="">UF:</label>
                        <p><?= $ct_provider->provider->address_uf ?></p>
                    </div>
                    <div class="col-md-12">
                        <label for="">Complemento:</label>
                        <p><?= empty($ct_provider->provider->address_complement) ? '---' : $ct_provider->provider->address_complement ?></p>
                    </div>
                    <div class="col-md-12" style="margin-top:20px;">
                         <!-- PARTE DE ANEXOS -->
                        <div
                            class="col-md-12"
                            style="background: rgba(222,222,222, .5); padding-bottom: 30px;"
                        >
                            <div class="col-md-12" style="margin: 25px 0 20px 0">
                                <label for="" style="text-transform:uppercase;">Arquivos Anexados</label>
                            </div>
                            <div class="col-md-12 anexos-listados scroll">
                                <div>
                                <?php $aux = 0; $issetAnexos = false;
                                    foreach ($cotation['cotation_attachments'] as $anexo) : ?>
                                    <?php if ($anexo['provider_id'] == $ct_provider['provider_id']) : ?>
                                        <?php $issetAnexos = true; ?>
                                        <input type="hidden" id="qtd-anexos-<?=$cont?>" value="<?=$aux++?>">
                                        <div class="col-sm-12">
                                            <span>
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
                                </div>
                            </div>
                        </div>
                    </div>
                    <div id="tabela-cotacoes" class="table-responsive" style="margin-left: 15px;">
                        <label style="text-transform:uppercase; margin: 10px 0 20px 0">Itens Cotados</label>
                        <table id="cotacoes" class="table table-details table-bordered table-responsive" style="border:none">
                            <div class="scrollmenu">
                                <thead>
                                    <tr class="table-titles">
                                        <th >Nome</th>
                                        <th>Categoria</th>
                                        <th>Quantidade</th>
                                        <th>Orçamento Unitário</th>
                                        <th>Fabricante</th>
                                        <th>Modelo</th>
                                        <th>Link</th>
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
                                            <td style="text-align:center"><a href="<?= $item->link_item ?>" target="_blank"><?=encurtarLink($item->link_item)?></a></td>
                                        </tr>
                                    <?php endif; ?>
                                    <?php endforeach; ?>
                                </tbody>
                            </div>
                        </table>
                    </div>
                </div>

                <!-- ====================================== -->
                    <!-- CORPO DO RELATÓRIO -->
                    <!-- =========================================== -->
                    <div id="report-body<?=$ct_provider->id?>" style="display:none">
                        <meta http-equiv="Content-Type" content="text/html;charset=UTF-8">
                        <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/2.2.4/jquery.min.js"></script>
                        <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/1.3.5/jspdf.min.js"></script>
                        <script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/0.4.1/html2canvas.min.js"></script>
                        <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@300;400;500;700;900&display=swap" rel="stylesheet">
                        <style>
                        /*Global*/
                        .report-content p, h1{
                            margin: 0;
                            padding: 0;
                        }

                        /*Reset*/
                        .report-content td, th, .table{
                            border: 1px solid #D0D2E1;
                        }
                        .report-content .col-12,
                        .report-content .col-7,
                        .report-content .col-6,
                        .report-content .col-4,
                        .report-content .col-3,
                        .report-content .col-2{
                            margin-bottom: 10px;
                        }
                        .report-main h2{
                            font-size: 16pt;
                        }
                        .report-main p{
                            font-size: 13pt;
                            font-weight: 500;
                        }
                        .report-main span{
                            font-size: 12pt;
                            font-weight: 300;
                        }

                        /*Customs*/
                        #report-body{
                            font-family: 'Roboto', sans-serif;
                            color: #232A30;
                        }
                        .report-header{
                            display: flex;
                            background: #F7F8FB;
                            padding:10px 30px;
                            align-items: center;
                            justify-content: center;
                        }
                        .report-header .title{
                            align-items: center;
                            margin-left: 15px;
                        }
                        .report-main{
                            position: relative;
                            padding: 40px 20px 20px 20px;
                        }
                        .report-main .details{
                            width: 100%;
                        }
                        .report-main .details .global{
                            position: absolute;
                            top: 30px;
                            right: 15px;
                            padding: 10px;
                            border: 1px solid #D0D2E1;
                            max-width: 400px;
                            max-height: 500px;
                        }
                        .report-main .details .data-patner,
                        .report-main .cotation-items{
                            margin-top: 40px;
                        }
                        .report-main .cotation-items header{
                            margin-bottom: 20px;
                        }
                        .report-main .cotation-items .table .tr-title{
                            background: #F7F8FB;
                            color: #004268;
                            font-size: 13pt;
                            font-weight: 500;
                        }
                        .report-main .cotation-items .table .tr-dynamic{
                            font-size: 12pt;
                            font-weight: 300;
                        }
                        .report-main .attachments{
                            margin-top: 30px;
                        }
                        .report-footer{
                            /* border-top: 1px solid rgba(35, 42, 48, .1); */
                            padding: 20px 0;
                            margin: 40px 15px 0 15px;
                            text-align: center;
                            color: #232A30;
                        }
                        </style>
                        <div class="container-fluid report-content" id="report-content<?=$ct_provider->id?>">
                            <header class="report-header">
                                <div>
                                <img
                                src="<?= $this->Url->build('/img/icons/logo-blue.png')?>"
                                alt="Logotipo NGProc">
                                </div>
                                <div class="title">
                                <h1>Relatório de cotação</h1>
                                <p>Visão de cliente</p>
                                </div>
                            </header>

                            <main class="report-main">
                                <div class="details">
                                <div class="row global">
                                    <div class="col-6">
                                    <p>ID Cotação Paga:</p>
                                    <span><?=$cotation->id?></span>
                                    </div>
                                    <div class="col-6">
                                    <p>Data de Pagamento:</p>
                                    <span><?= $purchase->payment_date ?></span>
                                    </div>
                                    <div class="col-6">
                                    <p>Valor do Frete:</p>
                                    <span><?php
                                        if($ct_provider->cost == 0){
                                            echo "Grátis";
                                        }else{
                                            echo "R$ " . $this->Number->format($ct_provider->cost, ['places' => 2, 'locale' => 'pt_BR']);
                                        }
                                    ?></span>
                                    </div>
                                    <div class="col-6">
                                    <p>Prazo de Entrega:</p>
                                    <span><?php
                                        if($ct_provider->deadline < 1){
                                            echo "Imediata";
                                        }else if($ct_provider->deadline == 1){
                                            echo $ct_provider->deadline . ' dia';
                                        }else{
                                            echo $ct_provider->deadline . ' dias';
                                        }
                                    ?></span>
                                    </div>
                                    <div class="col-12">
                                    <p>Código de Pagamento:</p>
                                    <span><?php
                                        if($purchase->discounted && $purchase->value == 0){
                                            echo "Nenhum";
                                        }else if(!empty($purchase->code_transaction) ){
                                            echo $purchase->code_transaction;
                                        }else{ echo 'Pagamento não efetuado'; }
                                    ?></span>
                                    </div>
                                    <div class="col-12">
                                    <p>Parceiro:</p>
                                    <span><?= formatarNomeFornecedor($cotation->user->name) ?></span>
                                    </div>
                                </div>

                                <div class="row data-client">
                                    <div class="col-12">
                                    <h2>Dados do Cliente</h2>
                                    </div>
                                    <div class="col-7">
                                    <p>Nome / Razão Social:</p>
                                    <span><?= $client->name ?></span>
                                    </div>
                                    <div class="col-7">
                                    <p>Endereço:</p>
                                    <span><?= $client->address_street ?> nº <?= $client->address_number ?>, <?= $client->address_zipcode ?>, <?= $client->address_neighborhood ?>, <?= $client->address_city ?> - <?= $client->address_uf ?> </span>
                                    </div>
                                    <div class="col-7">
                                    <p>CPF / CNPJ:</p>
                                    <span><?php
                                        if(!empty($client->doc_cpf)){
                                            echo $client->doc_cpf;
                                        }else if(!empty($client->doc_cnpj)){
                                            echo $client->doc_cnpj;
                                        }else{
                                            echo "Não informado";
                                        }
                                    ?></span>
                                    </div>
                                    <div class="col-7">
                                    <p>E-mail:</p>
                                    <span><?= $client->email ?></span>
                                    </div>
                                    <div class="col-7">
                                    <p>Contato:</p>
                                    <span><?php
                                        if(!empty($client->cellphone) && !empty($client->telephone)){
                                            echo $client->cellphone . " ou " . $client->telephone;
                                        }else if(!empty($client->cellphone)){
                                            echo $client->cellphone;
                                        }else if(!empty($client->telephone)){
                                            echo $client->telephone;
                                        }else{
                                            echo "Não informado";
                                        }
                                    ?></span>
                                    </div>
                                </div>

                                <div class="row data-patner">
                                    <div class="col-12">
                                    <h2>Dados do Fornecedor</h2>
                                    </div>
                                    <div class="col-6">
                                    <p>Razão Social / Nome Fantasia:</p>
                                    <span><?= $ct_provider->provider->name ?></span>
                                    </div>
                                    <div class="col-6">
                                    <p>CNPJ:</p>
                                    <span><?= $ct_provider->provider->cnpj ?></span>
                                    </div>
                                    <div class="col-6">
                                    <p>Website:</p>
                                    <span>
                                        <a href="<?= $ct_provider->provider->site ?>"><?= $ct_provider->provider->site ?></a>
                                    </span>
                                    </div>
                                    <div class="col-6">
                                    <p>Contato:</p>
                                    <span><?= empty($ct_provider->provider->telephone) ? 'Não informado' : $ct_provider->provider->telephone ?></span>
                                    </div>
                                    <div class="col-6">
                                    <p>Rua:</p>
                                    <span><?= $ct_provider->provider->address_street ?></span>
                                    </div>
                                    <div class="col-4">
                                    <p>CEP:</p>
                                    <span><?= $ct_provider->provider->address_zipcode ?></span>
                                    </div>
                                    <div class="col-2">
                                    <p>Número:</p>
                                    <span><?= $ct_provider->provider->address_number ?></span>
                                    </div>
                                    <div class="col-6">
                                    <p>Bairro:</p>
                                    <span><?= $ct_provider->provider->address_neighborhood ?></span>
                                    </div>
                                    <div class="col-4">
                                    <p>Cidade:</p>
                                    <span><?= $ct_provider->provider->address_city ?></span>
                                    </div>
                                    <div class="col-2">
                                    <p>UF:</p>
                                    <span><?= $ct_provider->provider->address_uf ?></span>
                                    </div>
                                    <div class="col-12">
                                    <p>Complemento:</p>
                                    <span><?= empty($ct_provider->provider->address_complement) ? 'Não informado' : $ct_provider->provider->address_complement ?></span>
                                    </div>
                                </div>
                                </div>

                                <div class="cotation-items">
                                <header>
                                    <h2>Itens Cotados</h2>
                                </header>
                                <table class="table">
                                    <thead>
                                    <tr class="tr-title">
                                        <th>Descrição</th>
                                        <th>Qtd.</th>
                                        <th>Valor Unitário</th>
                                        <th>Fornecedor</th>
                                        <th>Modelo</th>
                                        <th>Link</th>
                                        <th>Valor Total</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    <?php foreach ($cotation->cotation_product->cotation_product_items as $item) : ?>
                                    <?php if($item->provider_id == $ct_provider->provider_id): ?>
                                    <tr class="tr-dynamic">
                                        <td><?= $item->product->item_name ?></td>
                                        <td><?= $item->quantity ?></td>
                                        <td><?= "R$ " . $this->Number->format($item->quote, ['places' => 2, 'locale' => 'pt_BR']); ?></td>
                                        <td><?= $item->product->manufacturer ?></td>
                                        <td><?= $item->product->model ?></td>
                                        <td><a href="<?= $item->link_item ?>" target="_blank"><?=encurtarLink($item->link_item)?></a></td>
                                        <td><?= "R$ " . $this->Number->format(($item->quote * $item->quantity), ['places' => 2, 'locale' => 'pt_BR']); ?></td>
                                    </tr>
                                    <?php endif; ?>
                                    <?php endforeach; ?>
                                    </tbody>
                                </table>
                                </div>

                                <div class="attachments">
                                    <header>
                                        <h2>Arquivos Anexados</h2>
                                    </header>
                                    <?php
                                        $aux = 0; $issetAnexos = false;
                                        foreach ($cotation['cotation_attachments'] as $anexo) :
                                            if ($anexo['provider_id'] == $ct_provider['provider_id']) :
                                                $issetAnexos = true;
                                    ?>
                                    <div class="row">
                                        <div class="col-12">
                                            <a
                                            href="<?= $this->Url->build("/uploads/cotations/{$anexo->name}") ?>"
                                            download="<?= $anexo->name_original ?>"
                                            ><?= $anexo->name_original ?></a>
                                        </div>
                                    </div>
                                    <?php endif; ?>
                                    <?php endforeach; ?>
                                    <?php if(!$issetAnexos) : ?>
                                    <div class="row">
                                        <div class="col-6">
                                            <span>Nenhum arquivo encontrado</span>
                                        </div>
                                    </div>
                                    <?php endif;?>
                                </div>
                            </main>

                            <footer class="report-footer">
                                <div class="row">
                                <div class="col-12">
                                <?= date('Y') ?> ® NGProc, o seu parceiro de todos os dias.
                                </div>
                                </div>
                            </footer>
                        </div>
                    </div>
                    <!-- FIM CORPO DO RELATÓRIO -->
                    <!-- ======================================= -->

                <?php $cont++?>
                <?php endforeach; ?>
            </div>
        </div>
        <?php else : ?>
        <!-- ========================================
            TELA DE COTAÇÃO AGUARDANDO PAGAMENTO
        ======================================== -->
        <div class="ngproc-card-content">
            <div class="row">
                <div id="containerx" class="col-xs-12">
                    <div class="find-row">
                        <div id="nova"></div>
                        <form method="GET" class="form-horizontal">
                            <div class="form-group">
                                <label for="" class="col-sm-2 control-label">Buscar:</label>
                                <div class="col-sm-10 div-busca-parceiro">
                                    <input type="text" class="input-sm" name="cotacao-search">
                                    <span id="clear-search" title="Limpar busca" onclick="window.location.href='<?= $this->Url->build(['action' => 'index']) ?>'"><?= $this->Html->image('icons/delete-button.png') ?></span>

                                </div>
                            </div>
                        </form>
                    </div>
                </div>
                <div class="col-xs-12 col-md-12 table-responsive" style="padding-bottom:50px">
                    <div class="col-md-8" style="margin-bottom:10px">
                        <label for="">Código da Transação:</label>
                        <p><?= !empty($purchase->code_transaction) ? $purchase->code_transaction : 'Pagamento não efetuado' ?></p>
                    </div>
                    <div class="col-md-4" style="margin-bottom:10px">
                        <label for="">Opção de Pagamento:</label>
                        <p>PagSeguro</p>
                    </div>
                    <div class="col-md-4">
                        <label for="">Data da Transação:</label>
                        <p><?= $purchase->payment_date ?></p>
                    </div>
                    <div class="col-md-4">
                        <label for="">Valor à Pagar:</label>
                        <p><?= "R$ " . $this->Number->format($purchase->value, ['places' => 2, 'locale' => 'pt_BR']); ?></p>
                    </div>
                    <div class="col-md-4">
                        <label for="">Status:</label>
                        <p><?php
                            //STATUS BASEADO NA DOCUMENTAÇÃO DO PAGSEGURO
                                switch ($purchase->status) {
                                    case 0:
                                        echo "Aguardando PagSeguro";
                                        break;
                                    case 1:
                                        echo "Aguardando pagamento";
                                        break;
                                    case 2:
                                        echo "Em análise";
                                        break;
                                    case 3:
                                        echo "Paga";
                                        break;
                                    case 4:
                                        echo "Disponível";
                                        break;
                                    case 5:
                                        echo "Em disputa";
                                        break;
                                    case 6:
                                        echo "Devolvida";
                                        break;
                                    case 7:
                                        echo "Cancelada";
                                        break;
                                    case 8:
                                        echo "Debitada";
                                        break;
                                    case 9:
                                        echo "Retenção temporária";
                                        break;
                                    default:
                                    echo "Concluído";
                                }
                            ?></p>
                    </div>
                </div>
                <?php if($purchase->status == 7 || $purchase->status == 6): ?>
                <div class="row" style="margin-left:30px">
                    <button class='btn btn-primary' onclick="enviaPagseguro(<?= $cotation->id ?> , <?= $purchase->value ?>, <?= $cotation->main_cotation_id ?>, '<?=$purchase->code_transaction?>')">Tentar novamente</button>
                </div>
                <?php endif;?>
            </div>
        </div>
        <?php endif;?>
    </div>
</div>


<!-- Sweetalert -->
<script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
<script src="https://code.jquery.com/jquery-1.12.4.min.js" integrity="sha256-ZosEbRLbNQzLpnKIkEdrPv7lOy9C27hHQ+Xp8a4MxAQ=" crossorigin="anonymous"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/1.3.5/jspdf.min.js"></script>
   <script>
    function generateReport(id) {
        let element = '#report-body' + id;
        var divContents = $(element).html();
        var printWindow = window.open('about:blank');
        // printWindow.document.write('<html><head><title>DIV Contents</title>');
        // printWindow.document.write('</head><body>');
        printWindow.document.write('<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css" integrity="sha384-9aIt2nRpC12Uk9gS9baDl411NQApFmC26EwAOH8WgZl5MYYxFfc+NcPb1dKGj7Sk" crossorigin="anonymous">');
        printWindow.document.write(divContents);
        // printWindow.document.write('</body></html>');
        printWindow.document.close();
        printWindow.print();
    }

    function downloadPdfReport(id){
        let element = '#report-content' + id;
        let body = document.querySelector(element);
        var doc = new jsPDF("p", "mm", "a4");
        setTimeout(function(){
            html2canvas( $(element), {
                onrendered: function(canvas) {
                    var imgData = canvas.toDataURL('image/png');
                    //Page size in mm
                    var pageHeight = 295;
                    var imgWidth = 210;
                    var imgHeight = canvas.height * imgWidth / canvas.width;
                    var heightLeft = imgHeight;
                    var position = 0;

                    doc.addImage(imgData, 'PNG', 0, position, imgWidth, imgHeight);
                    heightLeft -= pageHeight;

                    while (heightLeft >= 0) {
                        position = heightLeft - imgHeight;
                        doc.addPage();
                        doc.addImage(imgData, 'PNG', 0, position, imgWidth, imgHeight);
                        heightLeft -= pageHeight;
                    }

                    // doc.autoPrint();
                    // doc.output('dataurlnewwindow');
                    doc.save('ngproc.pdf');
                }
            });
        }, 1000);
    }
</script>
<script>
$(document).ready(function(){
    // if($('#type-cotation').val() == 0){
        for (let i = 0; i < $('#qtd-envios').val(); i++) {
            let element = '#dados-fornecedor-' + i;
            if(i == 0){
                $(element).show();
            }else{
                $(element).hide();
            }
        }
    // }
});

// $("#btnPDF").click();
</script>
<script>
function reportPDF(){
    $.get("demo_test.asp", function(data, status){
    });
}

function avaliarParceiro() {

    let link = "<?= $this->Url->build([ "controller" => "Evaluations",
    "action" => "add"]) ?>";
    let parter_id = <?= $cotation['user']['id'] ?>;
    let value = $("input[name='fb']:checked"). val();
    $.post( link, { parter_id, value })
    .done(function( data ) {
        console.log(data);
    });
}

function trocarDeTela(tela){
    // if($('#type-cotation').val() == 0){
        let element = '#dados-fornecedor-' + tela;
        $(element).show();
        for (let i = 0; i < $('#qtd-envios').val(); i++) {
            element = '#dados-fornecedor-' + i;
            if(i != tela){
                $(element).hide();
            }
        }

    // }
}
</script>
<style>
.estrelas label{
    font-family: none!important;
}

.estrelas input[type=radio] {
  display: none;
}
.estrelas label i.fa:before {
  content:'\f005';
  color: #FC0;
  font-size: 40px;
}
.estrelas input[type=radio]:checked ~ label i.fa:before {
  color: #CCC;
}
</style>

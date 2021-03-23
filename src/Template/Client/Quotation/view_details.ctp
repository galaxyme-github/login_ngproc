<?php
if($purchase->status == 3){
    $this->assign('title', 'Detalhes do Pagamento');
}else{
    $this->assign('title', 'Detalhes da Transação');
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
<input type="hidden" id="type-cotation" value="<?=$cotation->type?>">
<input type="hidden" id="qtd-envios" value="<?=count($cotation->cotation_providers)?>">
<div class="dashboard-container">
    <div class="ngproc-card">
        <div class="ngproc-card-title">
            <!-- <?= $this->Html->link("< {$main_cotation->title}", ['action' => 'index'], ['class' => 'btn', 'id' => 'voltar']); ?> -->
            <?= $this->Html->link("< Minhas Cotações", ['action' => 'index'], ['class' => 'btn', 'id' => 'voltar']); ?>
        </div>
        <?php if($purchase->status == 3) : ?>
        <!-- ========================================
                TELA DE COTAÇÃO PAGA
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
                    <!-- <div class="col-md-12" style="margin:30px 0 15px 0">
                        <label for="">Detalhes da Cotação</label>
                    </div>
                    <div class="col-md-4">
                        <label for="">Parceiro:</label>
                        <p><?= $cotation->user->name ?></p>
                    </div>
                    <div class="col-md-4">
                        <label for="">E-mail:</label>
                        <p><?= $cotation->user->email ?></p>
                    </div>
                    <div class="col-md-4">
                        <label for="">Contato:</label>
                        <p><?= $cotation->user->telephone . ' ou ' .  $cotation->user->cellphone ?></p>
                    </div> -->
                </div>
            </div>
            <div class="row" style="margin-bottom:30px">
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
                                    <li id="aba-f<?=$cont?>"><input type="radio" name="tabs" class="rd_tabs" id="tab<?=$cont?>" <?= $cont == 0 ? 'checked' : '' ?>><label class="active" for="tab<?=$cont?>" onclick="trocarDeTela(<?=$cont?>)"><?= formatarNomeFornecedor($nomeFornecedor)?></label></li>
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
                        <p><?= $ct_provider->deadline . ' dias'?></p>
                    </div>
                    <div class="col-md-12" style="margin: 25px 0 20px 0">
                        <label for="">Dados do Fornecedor</label>
                    </div>
                    <div class="col-md-4">
                        <label for="">CNPJ:</label>
                        <p><?= $ct_provider->provider->cnpj ?></p>
                    </div>
                    <div class="col-md-4">
                        <label for="">Razão social ou Nome fantasia:</label>
                        <p><?= $ct_provider->provider->name ?></p>
                    </div>
                    <div class="col-md-4">
                        <label for="">Telefone:</label>
                        <p><?= $ct_provider->provider->telephone ?></p>
                    </div>
                    <div class="col-md-12">
                        <label for="">Site:</label>
                        <p><a href="<?= "http://" . $ct_provider->provider->site ?>" target="_blank"><?= encurtarLink($ct_provider->provider->site) ?></a></p>
                    </div>

                    <div class="col-md-7" style="margin-left: -15px">
                        <div class="col-md-12" style="margin: 25px 0 20px 0">
                            <label for="">Endereço</label>
                        </div>
                        <div class="col-md-8">
                            <label for="">Rua:</label>
                            <p><?= $ct_provider->provider->address_street ?></p>
                        </div>
                        <div class="col-md-4">
                            <label for="">Número:</label>
                            <p><?= $ct_provider->provider->address_number ?></p>
                        </div>
                        <div class="col-md-8">
                            <label for="">CEP:</label>
                            <p><?= $ct_provider->provider->address_zipcode ?></p>
                        </div>
                        <div class="col-md-4">
                            <label for="">Bairro:</label>
                            <p><?= $ct_provider->provider->address_neighborhood ?></p>
                        </div>
                        <div class="col-md-8">
                            <label for="">Cidade:</label>
                            <p><?= $ct_provider->provider->address_city ?></p>
                        </div>
                        <div class="col-md-4">
                            <label for="">UF:</label>
                            <p><?= $ct_provider->provider->address_uf ?></p>
                        </div>
                        <div class="col-md-12">
                            <label for="">Complemento:</label>
                            <p><?= empty($ct_provider->provider->address_complement) ? '---' : $ct_provider->provider->address_complement ?></p>
                        </div>
                    </div>

                    <!-- PARTE DE ANEXOS -->
                    <div
                        class="col-md-5"
                        style="background: rgba(222,222,222, .5); padding-bottom: 30px;"
                    >
                        <div class="col-md-12" style="margin: 25px 0 20px 0">
                            <label for="">Arquivos Anexados</label>
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
                    <div id="tabela-cotacoes" class="table-responsive" style="margin-left: 15px;">
                        <label style="margin: 25px 0 20px 0">Itens Cotados</label>
                        <table id="cotacoes" class="table table-details table-bordered table-responsive" style="border:none">
                            <div class="scrollmenu">
                                <thead>
                                    <tr class="table-titles">
                                        <th>Nome</th>
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
                                            <td style="text-align:center"><a href="<?= $item->link_item ?>" target="_blank"><?= encurtarLink($item->link_item) ?></a></td>
                                        </tr>
                                    <?php endif; ?>
                                    <?php endforeach; ?>
                                </tbody>
                            </div>
                        </table>
                    </div>
                </div>
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
</script>
<script>
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

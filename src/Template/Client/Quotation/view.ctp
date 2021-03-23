<?php $this->assign('title', 'Envios de Parceiros');
$menorValorOfertado = 0;
?>
<!-- Sweetalert -->
<script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
<script>
function previousPage(num) {
    if(num <= 0){
        swal({
            title: 'Ops',
            text: 'Não é possível voltar a lista. Este já é o primeiro item da cotação.',
            icon: 'info'
        });
    }else{
        let element = '#produto-'+ num;
        $(element).hide();
        element = '#produto-'+(num-1);
        $(element).show();
    }
}
function nextPage(num, max) {
    if( num == (max-1) ){
        swal({
            title: 'Ops',
            text: 'Não é possível avançar a lista. Este já é o último item da cotação.',
            icon: 'info'
        })
    }else{
        let element = '#produto-'+num;
        $(element).hide();
        element = '#produto-'+(num+1);
        $(element).show();
    }
}
</script>
<div class="dashboard-container">
    <div class="ngproc-card">
        <div class="ngproc-card-title">
            <!-- <?= $this->Html->link("< {$main_cotation->title}", ['action' => 'index'], ['class' => 'btn', 'id' => 'voltar']); ?> -->
            <?= $this->Html->link("< Minhas Cotações", ['action' => 'index'], ['class' => 'btn', 'id' => 'voltar']); ?>
        </div>
        <div class="ngproc-card-content">
            <div class="row">
                <div id="containerx" class="col-xs-12" style="margin-bottom:-10px">
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
                    <!-- CONTAINER PRODUTOS -->
                    <?php
                    if ($main_cotation['cotation_product'] != null) {
                        $count_items = 0;
                        $count_total_items = count($main_cotation['cotation_product']['cotation_product_items']);
                        foreach ($main_cotation['cotation_product']['cotation_product_items'] as $key => $mc) :
                            $count_items++;
                        ?>
                        <div
                            id="produto-<?=$key?>"
                            style="
                                background-color: #fff;
                                margin-bottom: 50px;
                                width: 100%;
                                max-height: 600px;
                            "
                            <?php if($key > 0) echo 'class="display-none"' ?>
                        >
                            <!-- HEADER -->
                            <div
                                style="
                                    width: 100%;
                                    padding: 0 15px;
                                    margin-bottom: 10px;
                                "
                            >
                                <p style="margin-bottom:5px">
                                    <label for="">Expectativa:</label>
                                    <?= 'Quantidade <strong>'.$mc['quantity'] . "</strong> e Valor <strong>R$ " . $this->Number->format($mc['quote'], ['places' => 2, 'locale' => 'pt_BR']).'</strong>' ?>
                                </p>
                                <div
                                    style="
                                        margin: 0 auto;
                                        width: 100%;
                                        text-align:center;
                                        background-color: #ecf0f5;
                                        font-weight: bold;
                                        padding: 10px 0
                                    "
                                >
                                    <span
                                        style="margin-right: 10px; cursor:pointer"
                                        onclick="previousPage(<?=$key?>)"
                                    >
                                        <img src="/img/icons/previous.png" alt="" width="18">
                                    </span>

                                    <span><?= strtoupper($mc['product']['item_name']) ?></span>

                                    <span
                                        style="margin-left: 10px; cursor:pointer"
                                        onclick="nextPage(<?=$key?>, <?=$count_total_items?>)"
                                    >
                                        <img src="/img/icons/next.png" alt="" width="18">
                                    </span>

                                </div>
                                <div
                                    style="
                                        width:<?= (($count_items * 100) / $count_total_items) . '%' ?>;
                                        height: 3px;
                                        background-color: #004268;
                                    "
                                    ></div>
                            </div>

                            <!-- TABELA COMPARATIVA -->
                            <div class="col-xs-12 col-md-12 table-responsive scroll"
                            style="
                                max-height: 500px;
                                overflow-y:scroll;
                                margin-bottom:50px
                            "
                            >
                                <table class="table table-bordered table-hover">
                                    <thead>
                                        <tr>
                                            <th>ID</th>
                                            <th>Valor unitário</th>
                                            <th>Quantidade</th>
                                            <th>Valor da entrega</th>
                                            <th>Prazo de entrega</th>
                                            <!-- <th>Fornecedor</th> -->
                                            <th width="50px">Prévia</th>
                                            <!-- <th width="50px">Aceitar</th> -->
                                            <!-- <th width="50px">Rejeitar</th> -->
                                            <!-- <th width="60px">Prévia</th> -->
                                        </tr>
                                    </thead>
                                    <tbody>
                                    <?php foreach ($cotations as $key => $value) :
                                        $menor_valor = -1;
                                        foreach ($cotations[$key]['cotation_product']['cotation_product_items'] as $k => $item){
                                            if($mc['product_id'] == $item['product_id']){
                                                if( $menor_valor < 0 || $menor_valor > $item['quote']){
                                                    $menor_valor = $item['quote'];
                                                    $items_id = $item['id'];
                                                    $items_qtd = $item['quantity'];
                                                    $provider_id = $item['provider_id'];
                                                }
                                            }
                                        }
                                    ?>
                                    <?php if($menor_valor >= 0) : ?>
                                        <tr>
                                            <td><?= $cotations[$key]['id'] ?></td>
                                            <td>
                                            <?= "R$ " . $this->Number->format($menor_valor, ['places' => 2, 'locale' => 'pt_BR']); ?>
                                            </td>
                                            <td><?=$items_qtd?></td>
                                            <?php
                                            foreach ($cotations[$key]['cotation_providers'] as $k => $c_provider) {
                                                if($c_provider['provider_id'] == $provider_id && $cotations[$key]['user']['id'] == $c_provider['user_id']){
                                                    $cost = $c_provider['cost'];
                                                    $deadline = $c_provider['deadline'];
                                                    $provider_name = strtoupper($c_provider['provider']['name']);
                                                }
                                            }
                                            ?>
                                            <td>
                                                <?php
                                                if($cost <= 0){
                                                    echo "Grátis";
                                                }else{
                                                    echo "R$ " . $this->Number->format($cost, ['places' => 2, 'locale' => 'pt_BR']);
                                                }
                                                ?>
                                            </td>
                                            <td>
                                            <?php
                                                if($deadline < 1){
                                                    echo "Imediata";
                                                }else if($deadline == 1){
                                                    echo $deadline . ' dia';
                                                }else{
                                                    echo $deadline . ' dias';
                                                }
                                            ?>
                                            </td>
                                            <!-- <td><?= $provider_name ?></td> -->
                                            <td style="text-align:center">
                                                <a
                                                    href="<?= $this->Url->build(['action' => 'view-preview', $value['id']]) ?>"
                                                    style="
                                                    background-image: url('<?=$this->Url->build("img/icons/previa.png")?>');
                                                    background-repeat: no-repeat;
                                                    background-size: 23px;
                                                    width:24px;
                                                    height:24px;" type="button" class="btn btn-link"
                                                >
                                                </a>
                                            </td>
                                        </tr>
                                        <?php $menor_valor = -1?>
                                    <?php endif ?>
                                        <?php endforeach ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                                            <?php endforeach; } else { ?>
                                                <p style="margin-bottom:5px">
                                    <label for="">Expectativa:</label>
                                    <?= 'Inicio <strong>'.$main_cotation->cotation_service->expectation_start . "</strong> e Valor <strong>R$ " . $this->Number->format($main_cotation->cotation_service->estimate, ['places' => 2, 'locale' => 'pt_BR']).'</strong>' ?>
                                </p>

  <table class="table table-bordered table-hover">
                                    <thead>
                                        <tr>
                                            <th>ID</th>
                                            <th>Título</th>
                                            <th>Descrição dos itens</th>
                                            <th>Valor unitário</th>
                                            <th>Tipo de cobrança</th>
                                            <th>Início</th>
                                            <th>Conclusão</th>
                                            <th width="50px">Prévia</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                    <tr>
                                    <?php foreach ($cotations as $key => $value) : ?>
                                        <td><?= $value['id']?></td>
                                        <td><?= $value['title']?></td>
                                        <td><?= $value->cotation_service->description?></td>
                                        <td> R$ <?= number_format($value->cotation_service->estimate,2,",",".") ?></td>
                                        <td><?= $value->cotation_service->collection_type ?></td>
                                        <td><?= $value->cotation_service->expectation_start?></td>
                                        <td><?= $value->deadline_date?></td>
                                        <td style="text-align:center">
                                                <a
                                                    href="<?= $this->Url->build(['action' => 'view-preview', $value['id']]) ?>"
                                                    style="
                                                    background-image: url('<?=$this->Url->build("img/icons/previa.png")?>');
                                                    background-repeat: no-repeat;
                                                    background-size: 23px;
                                                    width:24px;
                                                    height:24px;" type="button" class="btn btn-link"
                                                >
                                                </a>
                                            </td>
                                    </tr>


                                    <?php endforeach; }?>
                                    </tbody>
                                </table>
                    <!-- BOTÕES DE OPERAÇÃO -->
                    <div id="btn-group-send-patner" class="col-xs-12 col-md-12" style="padding-left:15px">
                        <a style="
                            padding-left: 30px;
                            padding-right: 30px;
                            color: white;
                            border-radius: 4px;"
                        href=""
                        class="btn btn-danger"
                        id="btn-cancelar">
                        Cancelar Cotação</a>
                        <a style="
                            padding-left: 55px;
                            padding-right: 55px;
                            background-color: black;
                            color:white;
                            border-radius: 4px;
                        " href="<?= $this->Url->build(['action' => 'index']) ?>" class="btn btn-dark" >Voltar</a>

                    </div>
               </div>
            </div>
        </div>
    </div>
</div>

<!-- Form Lightbox PagSeguro -->
<!-- POR HORA CÓDIGO NÃO UTILIZADO -->
<!-- <form id="comprar" action="https://pagseguro.uol.com.br/checkout/v2/payment.html" method="post" onsubmit="PagSeguroLightbox(this); return false;">
    <input type="hidden" name="code" id="code" value="" />
</form> -->
<!-- Form Lightbox PagSeguro -->
<script>
    function exibirAlerta() {
        swal({
            title: 'Atenção',
            text: 'Ao aceitar receber os dados dessa cotação, você concorda com o pagamento no valor de R$0,00.',
            icon: 'warning',
            button: "Prosseguir"
        });
    }
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
            window.location = "<?= $this->Url->build(['action' => 'cancel-cotation', $main_cotation->id]) ?>";
        } else {

        }
    });
});
</script>

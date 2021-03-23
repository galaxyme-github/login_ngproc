<?php $this->assign('title', 'Relatório');

/*
Menu
 */
?>
<style>
    .box-cashes{
        margin-left:0px;
        max-width:950px;
    }
    .box-cash{
        float:left;
        width: 25.5%;
        height: 130px;
        max-width: 326px;
        max-height: 326px;
        margin: 0 0 30px 0;
        padding: 15px 50px;
        border: 1px solid rgba(0, 66, 104, .5);
        background-color: #fff;
        border-radius: 5px;
        text-align: center;
        box-shadow: 3px 3px 3px rgba(0,0,0,.1);

        align-items: center;
        display: flex;
        flex-direction: row;
        flex-wrap: wrap;
        justify-content: center;
        position: relative;
    }
    .box-cash:nth-child(2){
        margin-right: 4.5%;
        margin-left: 4.5%;
        width: 40%;
        max-width: 665px;
    }
    .box-cash-info{
        position:absolute;
        top:10px;
        right:12px;
    }
    .box-cash span{
        font-size: 20px;
        font-weight: 600;
        color: #004268;
    }
    .box-cash span a,
    .box-cash span a:hover{
        color: #004268;
    }
    .box-cash span.green{
        color: #27ae60;/*#2ecc71;*/
    }
    .box-cash span.red{
        color: #e74c3c;
    }
    .box-cash .bold p{
        font-weight: bold;
    }
    .box-cash .box-cash-inside{
        display: grid;
        grid-template-columns: 1fr 1fr;
    }
    .box-cash .box-cash-inside div:nth-child(1){
        margin-right: 25px;
    }
    .box-cash .box-cash-inside div.flex{
        display: flex;
        align-items: center;
        justify-content: center;
    }
</style>
<!-- <div class="box-cashes">
    <div class="box-cash">
        <div>
            <p>Gerar Relatório</p>
            <span><a href="#" onclick="swal('Em breve funcional')">PDF <?= $this->Html->image('icons/download.png', ["width" => "18px", 'heigth' => "18px"]) ?></a></span>
        </div>
    </div>
    <div class="box-cash">
        <div>
            <p>Próxima Liberação</p>
            <div class="box-cash-inside">
                <div class="flex">
                    <span id="span-prox-comissao-valor"></span>
                </div>
                <div class="bold">
                    <p id="span-prox-comissao-data"></p>
                </div>
            </div>
        </div>
    </div> -->
    <!-- <div class="box-cash">
        <div class="box-cash-info">
            <a  href="#" 
                id="mytooltip"
                data-toggle="tooltip"
                title="Significa a porcentagem de comissões que já foram liberadas."
                data-placement="right">
                <?= $this->Html->image('icons/info.png', ["width" => "20px", 'heigth' => "20px"]) ?>
            </a>
        </div>
        <div>
            <p>Recebidas</p>
            <span id="span-porcento-pagas"></span>
            < !-- <span class="green" id="span-porcento-pagas" style="margin-right:30px"></span> -- >
            < !-- <span class="red" id="span-porcento-nao-pagas"></span> -- >
        </div>
    </div>
</div> -->
<div class="dashboard-container" style>
    <div class="ngproc-card">
        <div class="ngproc-card-title">
            Listagem de cotações aceitas
        </div>
        <!-- <a href="<?=$this->Url->build(['action' => 'insertCotations'])?>">Inserir Cotações</a> -->
        <div class="ngproc-card-content">
            <div class="row">
                <div id="containerx" class="col-xs-12">
                    <div class="find-row">
                        <div id="nova"></div>
                        <form method="GET" class="form-horizontal">
                            <div class="form-group">
                                <!-- <label for="" class="col-sm-2 control-label">Buscar:</label>
                                <div class="col-sm-10 div-busca-parceiro">
                                    <input type="text" class="input-sm" name="cotacao-search">
                                    <span id="clear-search" title="Limpar busca" onclick="window.location.href='<?= $this->Url->build(['action' => 'index']) ?>'"><?= $this->Html->image('icons/delete-button.png') ?></span>
                                </div> -->
                            </div>
                        </form>
                    </div>
                </div>
                <div class="col-xs-12 col-md-12 table-responsive">
                    <table class="clientes-tabela table table-bordered table-hover">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Título da Cotação</th>
                                <th>Data</th>
                                <th>Valor da Cotação</th>
                                <th>Valor Pago</th>
                                <th>Status</th>
                                <th>Prévia</th>
                                <th>Detalhes</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $PRAZO_DIAS_COMISSAO = "+7 days";
                            $qtdComissoes = empty($cotationPagasPorMim) == false ? count($cotationPagasPorMim) : 0;
                            $qtdComissoesPagas = 0;
                            $totalComissao = 0;
                            $dataMaisProxima = "";
                            $proximaComissao = array(
                                'valor' => 0,
                                'data' => '',
                            );
                            foreach ($cotationPagasPorMim as $cpc): ?>
                            <tr>
                                <td><?= $cpc['purchase']['cotation_id'] ?></td>
                                <td><?= $cpc['cotation']['title'] ?></td>
                                <td><?= $cpc['purchase']['payment_date'] ?></td>
                                <td><?php
                                    $menorValorOfertado = 0;
                                    if($cpc['cotation']['type'] == 0) {
                                        foreach ($cpc['cotation']['cotation_providers'] as $key => $cp) {
                                            $orcamento = 0;
                                            foreach ($cpc['cotation']['cotation_product']['cotation_product_items'] as $item) {
                                                if($cp->provider_id == $item->provider_id){
                                                    $orcamento = $orcamento + ($item->quote * $item->quantity);
                                                }
                                            }
                                            if($menorValorOfertado == 0){
                                                $menorValorOfertado = $orcamento;
                                            }else if($menorValorOfertado > $orcamento){
                                                $menorValorOfertado = $orcamento;
                                            }
                                        }
                                        echo "R$ " . $this->Number->format($menorValorOfertado, ['places' => 2, 'locale' => 'pt_BR']);
                                    }
                                ?></td>
                                <td><?= "R$ " . $this->Number->format($cpc['purchase']['value'], ['places' => 2, 'locale' => 'pt_BR']) ?></td>
                                <td><?php
                                    switch ($cpc['purchase']['status']) {
                                        case 0:
                                            echo "Aguardando PagSeguro";
                                            break;
                                        case 1:
                                            echo "<span style='color:#f1c40f'>Aguardando pagamento</span>";
                                            break;
                                        case 2:
                                            echo "<span style='color:#f1c40f'>Em análise</span>";
                                            break;
                                        case 3:
                                            echo "<span style='color:#2ecc71'>Paga</span>";
                                            break;
                                        case 4:
                                            echo "<span style='color:#2ecc71'>Paga</span>";
                                            break;
                                        case 5:
                                            echo "Em disputa";
                                            break;
                                        case 6:
                                            echo "Devolvida";
                                            break;
                                        case 7:
                                            echo "<span style='color:#e74c3c'>Cancelada</span>";
                                            break;
                                        case 8:
                                            echo "<span style='color:#2ecc71'>Paga</span>";
                                            break;
                                        case 9:
                                            echo "Retenção temporária";
                                            break;
                                        default:
                                        echo "<span style='color:#2ecc71'>Concluído</span>";
                                    }
                                ?></td>
                                <td style="text-align: center">
                                    <?php if(
                                        $cpc['purchase']['status'] != 3 
                                        && $cpc['purchase']['status'] != 4
                                        && $cpc['purchase']['status'] != 8
                                    ) : ?>
                                    <a  style="
                                        background-image: url('<?=$this->Url->build("img/icons/previa.png")?>');
                                        background-repeat: no-repeat;
                                        background-size: 23px;
                                        width:24px;
                                        height:24px;" type="button" class="btn btn-link" href="<?=$this->Url->build([ 'action' => 'preview', $cpc['purchase']['cotation_id']])?>">
                                    </a>
                                    <?php else : ?>
                                    ---
                                    <?php endif;?>
                                </td>
                                <td style="text-align: center">
                                <a  style="
                                    background-image: url('<?=$this->Url->build("img/icons/detalhes.png")?>');
                                    background-repeat: no-repeat;
                                    background-size: 23px;
                                    width:24px;
                                    height:24px;" class="btn btn-link" href="<?=$this->Url->build(['action' => 'details', $cpc['purchase']['cotation_id']])?>">
                                </a>
                                </td>
                            </tr>
                            <?php endforeach;?>
                            <input type="hidden" id="totalComissao" value="<?="R$ " . $this->Number->format($totalComissao, ['places' => 2, 'locale' => 'pt_BR']);?>">
                            <input type="hidden" id="proximaComissao-valor" value="<?= "R$ " . $this->Number->format($proximaComissao['valor'], ['places' => 2, 'locale' => 'pt_BR']) ?>">
                            <input type="hidden" id="proximaComissao-dias" value="<?= $proximaComissao['data'] == '' ? "Sem data prevista" : "Dia " . $proximaComissao['data'] ?>">
                            <?php if($qtdComissoesPagas != 0 && $qtdComissoes != 0) : ?>
                            <input type="hidden" id="porcentoPagas" value="<?= $this->Number->format(($qtdComissoesPagas * 100) / $qtdComissoes, ['precision' => 2, 'locale' => 'pt_BR']) ?>">
                            <!-- <input type="hidden" id="porcentoNaoPagas" value="<?= ( 100 - ($qtdComissoesPagas * 100) / $qtdComissoes ) ?>"> -->
                            <?php else :?>
                                <input type="hidden" id="porcentoPagas" value="0">
                                <!-- <input type="hidden" id="porcentoNaoPagas" value="0"> -->
                            <?php endif;?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap 3.3.7 -->
    <script src="<?= $this->Url->build('bower_components/bootstrap/dist/js/bootstrap.min.js') ?>"></script>
    <!-- Sweetalert -->
    <script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
    <script>
        $("#span-total").append($("#totalComissao").val());
        $("#span-prox-comissao-valor").append($("#proximaComissao-valor").val());
        $("#span-prox-comissao-data").append($("#proximaComissao-dias").val());
        // $("#span-porcento-pagas").append($("#porcentoPagas").val() + "%");
        // $("#span-porcento-nao-pagas").append($("#porcentoNaoPagas").val() + "%");
    </script>
    <script>
    $(document).ready(function(){ 
        $('#mytooltip').tooltip();
    });
    </script>
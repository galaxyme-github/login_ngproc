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
<div class="box-cashes">
    <div class="box-cash">
        <div class="box-cash-info">
            <!-- <a  href="#" 
                id="mytooltip"
                data-toggle="tooltip"
                title="Ao transferir o saldo será debitado o valor de R$ 3,50 referente a taxa de transferência."
                data-placement="right">
                <?= $this->Html->image('icons/info.png', ["width" => "20px", 'heigth' => "20px"]) ?>
            </a> -->
            <!-- <a href=""></a> -->
        </div>
        <div>
            <p>Valor Liberado</p>
            <span id="span-total"></span>
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
    </div>
    <!--<div class="box-cash">
        <div>
            <p>Gerar Relatório</p>
            <span><a href="#" onclick="swal('Em breve funcional')">PDF <?= $this->Html->image('icons/download.png', ["width" => "20px", 'heigth' => "20px"]) ?></a></span>
        </div>
    </div>-->
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
    </div> -->
</div>
<div class="dashboard-container" style>
    <div class="ngproc-card">
        <div class="ngproc-card-title">
            
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
                <div class="col-xs-12 col-md-12 table-responsive" style="min-height:120px;max-height: 450px">
                    <table class="clientes-tabela table table-bordered table-hover">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Valor Base
                                <a  href="#" 
                                    id="mytooltip"
                                    data-toggle="tooltip"
                                    title="É o maior valor entre o valor estimado pelo cliente e o valor da menor cotação apresentada por você."
                                    data-placement="bottom"
                                    style="margin-left:10px">
                                    <?= $this->Html->image('icons/info.png', ["width" => "16px", 'heigth' => "16px"]) ?>
                                </a>
                                </th>
                                <th>Valor Devido
                                <a  href="#" 
                                    id="mytooltip"
                                    data-toggle="tooltip"
                                    title="Este é o valor bruto de sua comissão."
                                    data-placement="bottom"
                                    style="margin-left:10px">
                                    <?= $this->Html->image('icons/info.png', ["width" => "16px", 'heigth' => "16px"]) ?>
                                </a>
                                </th>
                                <th>Valor Efetivo
                                <a  href="#" 
                                    id="mytooltip"
                                    data-toggle="tooltip"
                                    title="É o valor líquido de sua comissão após serem aplicadas taxas e cobranças."
                                    data-placement="bottom"
                                    style="margin-left:10px">
                                    <?= $this->Html->image('icons/info.png', ["width" => "16px", 'heigth' => "16px"]) ?>
                                </a>
                                </th>
                                <th>Data Prevista</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $PRAZO_DIAS_COMISSAO = "+7 days";
                            $qtdComissoes = empty($cotationParaComissao) == false ? count($cotationParaComissao) : 0;
                            $qtdComissoesPagas = 0;
                            $totalComissao = 0;
                            $dataMaisProxima = "";
                            $proximaComissao = array(
                                'valor' => 0,
                                'data' => '',
                            );
                            foreach ($cotationParaComissao as $cpc): ?>
                            <tr>
                                <td><?= $cpc['purchase']['cotation_id'] ?></td>
                                <td>
                                    <?php
                                        $value = number_format($cpc['cotation']['cotation_product']['estimate'], 2, '.', '');
                                    ?>
                                    <?="R$ " . $this->Number->format($value, ['places' => 2, 'locale' => 'pt_BR']); ?>
                                </td>
                                <td>
                                    <!-- Valor Devido -->
                                    <!-- É os 2% do valor base (Comissão) menos 30% de cobrança. -->
                                    <!-- Exemplo: ( CM * 70 ) / 100 = CM -->
                                    <!-- Legenda: CM - Comissão -->
                                    <?php
                                        if($value < 350) { $valorBase = 7; }
                                        else{ $valorBase = ( ($value * 2 ) / 100 ); }
                                        
                                        $comissao = ( (  $valorBase * 70 ) / 100 );
                                        $comissao = number_format($comissao, 2, '.', '');
                                        echo "R$ " . $this->Number->format($comissao, ['places' => 2, 'locale' => 'pt_BR']);
                                    ?>
                                </td>
                                <td>
                                    <!-- Valor Efetivo -->
                                    <!-- É 2% do valor base menos, R$ 0,40 de taxa de utilização do PagSeguro -->
                                    <!-- mais 5% do Valor Devido (Sem considerar os 30% de cobrança) -->
                                    <!-- Cálculo: VD - (0.40 + ( (VD * 5) / 100 ) ) = VE -->
                                    <!-- Lengenda: VD - Valor Devido, VB - Valor Base -->
                                    <?php
                                        $ve = $comissao - ( 0.40 + ( ($valorBase * 5) / 100 ) );
                                        $ve = number_format($ve, 2, '.', '');
                                        if($cpc['purchase']['commission_pay'] != 1){
                                            $totalComissao += $ve;
                                        }
                                        echo "R$ " . $this->Number->format($ve, ['places' => 2, 'locale' => 'pt_BR']);
                                    ?>
                                </td>
                                <td><?php
                                    //Formatando data
                                    $result = explode("/",$cpc['purchase']['payment_date']);
                                    $result = $result[2]."/".$result[1] ."/".$result[0];

                                    //Verificando data mais proxima
                                    
                                    // FORMATO ORDER BY DESC
                                    // if($cpc['purchase']['commission_pay'] != 1){
                                    //     if(empty( $dataMaisProxima ) || strtotime( strtotime( $PRAZO_DIAS_COMISSAO, strtotime( $dataMaisProxima ) ) ) < strtotime( $PRAZO_DIAS_COMISSAO, strtotime( $result ) ) ){
                                    //         $dataMaisProxima = $result;
    
                                    //         $proximaComissao['valor'] = $ve;
                                    //         $proximaComissao['data'] = date("d/m/Y", strtotime( $PRAZO_DIAS_COMISSAO, strtotime( $dataMaisProxima ) ) );
                                    //     }
                                    // }

                                    // FORMATO ORDER BY ASC
                                    if($cpc['purchase']['commission_pay'] != 1){
                                        if(empty( $dataMaisProxima ) || strtotime( strtotime( $PRAZO_DIAS_COMISSAO, strtotime( $dataMaisProxima ) ) ) > strtotime( $PRAZO_DIAS_COMISSAO, strtotime( $result ) ) ){
                                            $dataMaisProxima = $result;
    
                                            $proximaComissao['valor'] = $ve;
                                            $proximaComissao['data'] = date("d/m/Y", strtotime( $PRAZO_DIAS_COMISSAO, strtotime( $dataMaisProxima ) ) );
                                        }
                                    }

                                    //exibindo data de previsão para a NGPROC pagar o parceiro
                                    echo date("d/m/Y", strtotime( $PRAZO_DIAS_COMISSAO, strtotime( $result ) ) );
                                ?></td>
                                <td>
                                <?php
                                switch ($cpc['purchase']['commission_pay']) {
                                    case 1:
                                        $qtdComissoesPagas++;
                                        echo "<span style='color:#2ecc71'>Recebido</span>";
                                        // echo "Data que pagou";
                                        break;
                                    
                                    default:
                                        echo "A receber";
                                        break;
                                }
                                ?>
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
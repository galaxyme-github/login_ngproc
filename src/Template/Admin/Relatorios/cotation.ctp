<?php $this->assign('title', 'Cotações');
/*
Menu
 */
?>
<div class="dashboard-container" style>
    <div class="ngproc-card">
        <div class="ngproc-card-title">
            <div class="ngproc-card-title-button" style="padding-top:4px">
                <?= $this->Html->link('< Relatórios', ['action' => 'index'], ['class' => 'btn', 'id' => 'voltar']); ?>
            </div>
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
                                <th>Parceiro</th>
                                <th>Cliente</th>
                                <th>Data da Transação</th>
                                <th>Status</th>
                                <th width="50px">Detalhar</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($purchasesCotations as $pc) : ?>
                            <tr>
                                <td><?=$pc['cotation']['id']?></td>
                                <td><?=$pc['cotation']['user']['name']?></td>
                                <td><?=$pc['main_cotation']['user']['name']?></td>
                                <td><?= empty($pc['purchase']['payment_date']) ? 'Não realizada' : $pc['purchase']['payment_date'] ?></td>
                                <td>
                                    <?php
                                        //STATUS BASEADO NA DOCUMENTAÇÃO DO PAGSEGURO
                                        switch ($pc['purchase']['status']) {
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
                                                echo "Disponível";
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
                                                echo "Debitada";
                                                break;
                                            case 9:
                                                echo "Retenção temporária";
                                                break;
                                            default:
                                            echo "<span style='color:#2ecc71'>Concluído</span>";
                                        }
                                    ?>
                                </td>
                                <td class="arrow">
                                    <img
                                        style="cursor:pointer"
                                        src="/img/icons/detalhes.png"
                                        width="22px"
                                        onclick="window.location.href='<?= $this->Url->build(['action' => 'detail', $pc['cotation']['id']]) ?>'"
                                    >
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
                <div id="" class="col-xs-12 col-md-12">
                    <ul class="pagination pagination-sm no-margin pull-right">
                        <?php
                        echo $this->Paginator->prev('<');
                        echo $this->Paginator->numbers(['modulus' => 4]);
                        echo $this->Paginator->next('>');
                        ?>
                    </ul>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap 3.3.7 -->
    <script src="<?= $this->Url->build('bower_components/bootstrap/dist/js/bootstrap.min.js') ?>"></script>
    <!-- Sweetalert -->
    <script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
    <script>
    $(document).ready(function(){ 
        $('#mytooltip').tooltip();
    });
    </script>
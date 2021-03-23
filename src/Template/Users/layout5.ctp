<?php $this->assign('title', 'Cotações Recebidas');

/*
Menu
 */

?>


<div class="dashboard-container">
    <div class="ngproc-card">
        <div class="ngproc-card-title">
        <?=$this->Html->link('< Dados da cotação', ['action' => 'index'], ['class' => 'btn', 'id' => 'voltar']);?>
        </div>
        <div class="ngproc-card-content">
            <div class="row">
                <div id="containerx" class="col-xs-12">
                    <div class="find-row">
                        <div id="nova">
                           <?=$this->Html->link('   Nova cotação', ['action' => 'add'], ['class' => 'cliente-cadastro btn btn-primary btn-sm']);?>
                        </div>
                        <form method="GET" class="form-horizontal">
                            <div class="form-group" id="buscar">
                                <label for="" class="col-sm-2 control-label">Buscar:</label>
                                <div  class="col-sm-10 div-busca-parceiro">
                                    <input type="text" class="input-sm" name="cotacao-search">
                                    <span id="clear-search" title="Limpar busca" onclick="window.location.href='<?=$this->Url->build(['action' => 'index'])?>'"><?=$this->Html->image('icons/delete-button.png')?></span>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
                <div class="col-xs-12 col-md-12 table-responsive">
                    <table class="clientes-tabela table table-bordered table-hover">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Título</th>
                                <th>Expectativa de orçamento</th>
                                <th>Data de inclusão</th>
                                <th>Prazo para conclusão</th>
                                <th>Status</th>
                                <th width="50px"></th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($cotations as $cotation): ?>
                                <tr class="tr-link" onclick="<?=$cotation->status == 0 ? 'exibirAlerta()' : "window.location.href=''"?>">
                                    <td><?=$cotation->id?></td>
                                    <td><?=$cotation->title?></td>
                                    <td><?="R$" . $this->Number->format($cotation->getBudgetExpectation(), ['places' => 2, 'locale' => 'pt_BR'])?></td>
                                    <td><?=$cotation->created->format('d/m/Y')?></td>
                                    <td><?=$cotation->deadline_date?></td>
                                    <td><?=$cotation->status == 0 ? "Aguardando parceiros" : ""?></td>
                                    <td class="arrow"><img src="/img/icons/arrow-point-to-right.png"></td>
                                </tr>
                            <?php endforeach;?>
                        </tbody>
                    </table>

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
</div>

<!-- Bootstrap 3.3.7 -->
<script src="<?=$this->Url->build('bower_components/bootstrap/dist/js/bootstrap.min.js')?>"></script>
<!-- Sweetalert -->
<script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
<script>

</script>
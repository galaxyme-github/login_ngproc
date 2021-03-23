<?php $this->assign('title', 'Meus Envios');?>

<div class="dashboard-container">
    <div class="ngproc-card">
        <div class="ngproc-card-title">
            Listagem de cotações
        </div>
        <div class="ngproc-card-content">
            <div class="row">
                <div id="containerx" class="col-xs-12">
                    <div class="find-row">
                        <div id="nova"></div>
                        <form method="GET" class="form-horizontal">
                            <div class="form-group">
                                <label for="" class="col-sm-2 control-label">Buscar:</label>
                                <div  class="col-sm-10">
                                    <input type="text" class="input-sm" name="cotacao-search"/>
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
                                <th>Cotação</th>
                                <th>Menor Orçamento</th>
                                <th>Expec. do cliente</th>
                                <th>Data de envio</th>
                                <th>Prazo para conclusão</th>
                                <th>Status</th>
                                <th width="50px" colspan="2" style="text-align: center">Ação</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if(!empty($cotations)) : ?>
                            <?php foreach ($cotations as $k => $cotation): ?>
                                    <tr class="tr-link">
                                        <td><?=$cotation->id?></td>
                                        <td><?=$cotation->title?></td> <!--Corrigir aqui para listar os productsItems -->
                                        <td><?php
                                            if($cotation->type == 0){
                                                echo "Produto";
                                            }else if($cotation->type == 1){
                                                echo "Serviço";
                                            }else{
                                                echo "Outro";
                                            }
                                            ?>
                                        </td>
                                        <td><?php
                                            if($cotation->type == 0){
                                                $menorOrcamento = 0;
                                                foreach ($cotation['cotation_providers'] as $key => $cp) {
                                                    $orcamento = 0;
                                                    foreach ($cotation['cotation_product']['cotation_product_items'] as $item) {
                                                        if($cp->provider_id == $item->provider_id){
                                                            $orcamento = $orcamento + ($item->quote * $item->quantity);
                                                        }
                                                    }
                                                    if($menorOrcamento == 0){
                                                        $menorOrcamento = $orcamento;
                                                    }else if($menorOrcamento > $orcamento){
                                                        $menorOrcamento = $orcamento;
                                                    }
                                                }
                                                echo "R$ " . $this->Number->format($menorOrcamento, ['places' => 2, 'locale' => 'pt_BR']);
                                            }else{
                                                echo "R$ " . $this->Number->format($cotation->getBudgetExpectation(), ['places' => 2, 'locale' => 'pt_BR']);
                                            }
                                        ?>
                                        </td>
                                        <td><?php
                                            if(count($clienteOrcamento) != 0){
                                                if($cotation->id == $clienteOrcamento[$k]['idEnviada']){
                                                    echo "R$ " . $this->Number->format($clienteOrcamento[$k]['expecCliente'], ['places' => 2, 'locale' => 'pt_BR']);
                                                }else{
                                                    echo "---";
                                                }
                                            }
                                        ?>
                                        </td>
                                        <td><?=$cotation->created->format('d/m/Y')?></td>
                                        <td><?=$cotation->deadline_date?></td>
                                        <td>
                                            <?php
                                                switch ($cotation->status) {
                                                    case 0:
                                                        echo "Cotação Enviada";
                                                    break;
                                                    case 1:
                                                        echo "Cotação Enviada";
                                                        break;
                                                    case 2:
                                                        echo "Cotação Expirada";
                                                        break;
                                                    case 3:
                                                        echo "<span style='color:#e74c3c'>Cotação Cancelada</span>";
                                                        break;
                                                    case 4:
                                                        echo "<span style='color:#e74c3c'>Cotação Rejeitada</span>";
                                                        break;
                                                    default:
                                                        echo "<span style='color:#2ecc71'>Cotação Aprovada</span>";
                                                }
                                            ?>
                                        </td>
                                        <td class="arrow">
                                            <img
                                                src="/img/icons/previa.png"
                                                width="18"
                                                onclick="window.location.href='<?=$this->Url->build(['action' => 'view', $cotation->id])?>'"
                                            >
                                        </td>
                                        <td class="arrow">

                                        <?php if($cotation->status != 3 && $cotation->status != 4 && $cotation->status != 5 && $cotation->purchase == null) : ?>
                                            <img
                                                src="/img/icons/arrow-point-to-right.png"
                                                onclick="window.location.href='<?=$this->Url->build(['controller' => 'Quotation', 'action' => 'edit', $cotation->id])?>'"
                                            >
                                        <?php else : ?>
                                            ---
                                        <?php endif; ?>
                                        </td>
                                    </tr>
                                <?php endforeach;?>
                            <?php endif; ?>
                        </tbody>
                    </table>
                    <?php
                        //MOSTARNDO MENSAGEM CASO NÃO TENHA NENHUMA COTAÇÃO
                        if(count($cotations) <= 0){
                            echo "<div style='text-align:center'>Você ainda não possui nenhum envio.</div>";
                        }
                    ?>
                <div id="" class="col-xs-12 col-md-12">
                    <ul class="pagination pagination-sm no-margin pull-right">
                    <?php
                        echo $this->Paginator->prev('<');
                        if(!empty($cotations)){
                            echo $this->Paginator->numbers(['modulus' => 4]);
                        }

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
    let firstLogin = <?=$firstLogin ? 'true' : 'false'?>;
    if (firstLogin) {
        swal({
            title: "Bem vindo!",
            text: `Parceiro NGP,
Seja muito bem-vindo(a) a NGProc.  Estaremos trabalhando juntos para oferecermos aos nossos clientes os melhores preços e condições.  E lembre-se ao enviar uma cotação tenha certeza de que as informações estejam corretas e que os preços sejam competitivos.
Desejamos sucesso!
NGProc`,
            button: "Ok",
        });
    }
</script>

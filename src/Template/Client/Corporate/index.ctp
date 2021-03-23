<?php $this->assign('title', 'Corporate');

$cotationVazia = false;
/*
Menu
 */

?>


<div class="dashboard-container">
    <div class="ngproc-card">
        <div class="ngproc-card-title">
            Listagem de Projetos
        </div>
        <div class="ngproc-card-content">
            <div class="row">
                <div id="containerx" class="col-xs-12">
                    <div class="find-row">
                        <div id="nova">
                           <?=$this->Html->link('Novo Projeto', ['action' => 'add'], ['class' => 'btn btn-primary btn-sm', 'id' => 'btn-novo-projeto']);?>
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
                                <th>Data de inclusão</th>
                                <th>Status</th>
                                <th width="100px"></th>
                            </tr>
                        </thead>
                        <tbody>
                        </tbody>
                    </table>
                </div>
                <div style="text-align: center">
                    Você ainda não possui nenhum projeto
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
</div>
<button class="display-none" id="btn-abrir-modal" data-toggle="modal" data-target="#modal">Modal</button>
<div class="modal fade" id="modal" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close" id="btn-close-modal"><span aria-hidden="true">&times;</span></button>
                <h3 class="modal-title">NG PROC CORPORATE <img src="<?=$this->Url->build('img/icons/corporate-a.png')?>" alt="Corporate" width="27" height="19" style="margin:-6px 0 0 5px"></h3>
            </div>
            <div class="modal-body">
            <p>Nossos parceiros Corporate desenvolvem projetos de alta complexidade.</p>
            <p>O processo é bem simples:</p>
            <ol>
                <li>Adquira horas de consultoria;</li>
                <li>Descreva seu projeto;</li>
                <li>Estabeleça os KIP's (Key Process Indicator ou Metas);</li>
            </ol>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary" id="btn-fechar-modal" data-dismiss="modal">Prosseguir</button>
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->

<!-- Bootstrap 3.3.7 -->
<script src="<?=$this->Url->build('bower_components/bootstrap/dist/js/bootstrap.min.js')?>"></script>
<!-- Sweetalert -->
<script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
<script>
    $('#btn-fechar-modal').click(function(){
        window.location = "<?=$this->Url->build([ 'controller' => 'Corporate', 'action' => 'add'])?>";
    });
    let firstLogin = <?=$firstLogin ? 'true' : 'false'?>;
    if (firstLogin) {
        $('#btn-abrir-modal').click();
    }
</script>


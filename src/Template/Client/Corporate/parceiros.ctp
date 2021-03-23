<?php $this->assign('title', 'Corporate');
/*
Menu
 */

?>


<div class="dashboard-container">
    <div class="ngproc-card">
        <div class="ngproc-card-title">
            Listagem de Parceiros
        </div>
        <div class="ngproc-card-content">
            <div class="row">
                <div id="containerx" class="col-xs-12">
                    <div class="find-row">
                        <div id="nova"></div>
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
                                <th width="100px">Foto</th>
                                <th>Nome</th>
                                <th>Destaques de qualificação</th>
                                <th>Avaliação</th>
                                <th>Valor/hora</th>
                                <th>Status</th>
                                <th width="100px"></th>
                            </tr>
                        </thead>
                        <tbody>
                        </tbody>
                    </table>
                </div>
                <div style="text-align: center">
                    Não há parceiros participando do corporate ainda.
                </div>
                <div id="juntos">
                    <?=$this->Html->link('Voltar', ['action' => 'index'], ['class' => 'btn btn-dark', 'id' => 'preto']);?>
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


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
                <div>
                    <div class="content-left col-sm-8">
                        <div class="title-left h5 col-sm-12">
                            Dados da cotação
                        </div>
                        <div class="row">
                            <div class="form-group has-feedback input-linha col-sm-6">
                                <label for="exp-orcamento">Expectativa de orçamento</label>
                                <input type="text" class="form-control" id="exp-orcamento" name="">
                            </div>
                            <div class="form-group has-feedback input-linha col-sm-6">
                                <label for="name">Prazo para conclusão</label>
                                <input type="text" class="form-control" id="title_product" name="">
                            </div>
                        </div>
                        <div class="row">
                            <div class="form-group has-feedback input-linha col-sm-6">
                                <label for="abrangencia">Abrangência da cotação</label>
                                <input type="text" class="form-control" id="abrangencia" name="">
                            </div>
                            <div class="form-group has-feedback input-linha col-sm-6">
                                <label for="descricao">Descrição</label>
                                <input type="text" class="form-control" id="descricao" name="">
                            </div>
                        </div>
                    </div>
                    <div class="content-right float-right col-sm-4">
                        <div class="title-left h5 col-sm-12">
                            Arquivos anexados
                        </div>
                        <div class="col-sm-12">
                            anexo1(teste)
                        </div>
                        <div class="col-sm-12">
                            anexo2(teste)
                        </div>
                    </div>
                </div>
                <div id="tabela-cotacoes" class="table-responsive" style="margin-left: 15px;">
                        <table id="cotacoes" class="table table-bordered table-responsive">
                            <div class="scrollmenu">
                                <thead>
                                    <tr>
                                        <th>Nome</th>
                                        <th>Categoria</th>
                                        <th>Qtd</th>
                                        <th>Orçamento</th>
                                        <th>Fabricante</th>
                                        <th>Modelo</th>
                                        <th>SKU</th>
                                        <th>Fornecedor</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    
                                </tbody>
                            </div>
                        </table>
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
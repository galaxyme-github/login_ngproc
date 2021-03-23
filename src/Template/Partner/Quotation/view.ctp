<?php $this->assign('title', 'Cotação Recebida');

if (!empty($cotation['cotation_product'])) {
    $qtdCotationProdutosItens = count($cotation['cotation_product']['cotation_product_items']);
} else {
    $qtdCotationProdutosItens = -1;
}
//dump($cotation);
/*
Menu
 */
/*
Como isso é uma view apenas, vc pode tirar esses input, imprime o texto direto
ai vc valida se é produto ou serviço e imprime o conteudo de acordo
dxa como readonly entao, ai vc poe o fundo branco dnv

*/
//dump($user);
?>
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.3.0/css/font-awesome.min.css">

<!-- GUARDANDO A INFORMAÇÃO, SE OS DADOS BANCÁRIOS ESTÃO OU NÃO COMPLETOS -->
<input type="hidden" id="dados-bancarios-incompletos" value="<?php
    if( empty($user->bank_username) ||
        empty($user->bank_cpf) ||
        empty($user->bank_agency) ||
        empty($user->bank_account) ||
        empty($user->bank_name)
    ){
        echo 1; // == 1 : Dados bancários incompletos
    }else{
        echo 0; // == 0 : Dados bancários completos
    }
?>">

<div class="dashboard-container">
    <div class="ngproc-card">
        <div class="ngproc-card-title cards-abas">
            <div class="ngproc-card-title-button" style="padding-top:4px">
                <?= $this->Html->link('< Cotações Recebidas', ['action' => 'index'], ['class' => 'btn', 'id' => 'voltar']); ?>
            </div>
            <div class="ngproc-card-title-abas">
                <nav class="nav_tabs">
                    <ul id="nav_tabs_ul">
                    <!-- As abas dos fornecedores aparecerão aqui -->
                    </ul>
                </nav>
            </div>
        </div>
        <!-- <?= $this->Form->create(null, ['id' => 'responsecotation-form', 'type' => 'file']) ?> -->
        <input type="hidden" id="cotation_enviada" />
        <input type="hidden" id="main_cotation_id" value="<?= $cotation->id ?>" />
        <input type="hidden" id="type-cotation" value="<?= $cotation->type ?>" />
        <input type="hidden" id="provider_qtd" value="<?= $cotation->provider_qtd ?>" />
        <input type="hidden" id="title-cotation" value="<?=$cotation->title?>">
        <div>
            <!--
                ====================================================
                            COTAÇÃO DE SERVIÇO
                ====================================================
            -->
            <?php if($cotation->type == 1) : ?>
            <!-- TELA 1 - SERVIÇO -->
            <div id="tela1" class="ngproc-card-content" style="padding-left:25px">
                <div class="row">
                    <div id="partner-view-service">
                        <div>
                            <div class="content-left col-sm-8">
                                <div class="title-left h5 col-sm-12">
                                    Dados da cotação
                                </div>
                                <div class="row">
                                    <div class="form-group has-feedback input-linha col-sm-6">
                                        <label>Título</label>
                                        <input type="text" class="form-control" value="<?= $cotation->title ?>" readonly="readonly">
                                    </div>
                                    <div class="form-group has-feedback input-linha col-sm-6">
                                        <label >Categoria</label>
                                        <input type="text" class="form-control" value="<?= $cotation->cotation_service->getCategoryName() ?>" readonly="readonly">
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="form-group has-feedback input-linha col-sm-6">
                                        <label>Descrição</label>
                                        <input type="text" class="form-control" value="<?= $cotation->cotation_service->description; ?>" readonly="readonly">
                                    </div>
                                    <div class="form-group has-feedback input-linha col-sm-6">
                                        <label>Abrangência da cotação</label>
                                        <input type="text" class="form-control" value="<?= $cotation->coverage; ?>" readonly="readonly">
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="form-group has-feedback input-linha col-sm-6">
                                        <label>Tipo de cobrança</label>
                                        <input type="text" class="form-control" value="<?= $cotation->cotation_service->collection_type; ?>" readonly="readonly">
                                    </div>
                                    <div class="form-group has-feedback input-linha col-sm-6">
                                        <label>Prazo para conclusão</label>
                                        <input type="text" class="form-control" value="<?= $cotation->deadline_date; ?>" readonly="readonly">
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="form-group has-feedback input-linha col-sm-6">
                                        <label>Expectativa de orçamento</label>
                                        <input type="text" class="form-control" value="<?= "R$ " . $this->Number->format($cotation->cotation_service->estimate, ['places' => 2, 'locale' => 'pt_BR']) ?>" readonly="readonly">
                                    </div>
                                    <div class="form-group has-feedback input-linha col-sm-6">
                                        <label>Expectativa de início de serviço</label>
                                        <input type="text" class="form-control" value="<?= $cotation->cotation_service->expectation_start; ?>" readonly="readonly">
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="form-group has-feedback input-linha col-sm-6">
                                        <label>Tempo estimado de demanda do serviço</label>
                                        <input type="text" class="form-control" value="<?= $cotation->cotation_service->service_time; ?>" readonly="readonly">
                                    </div>
                                </div>
                            </div>
                            <div class="content-right  col-sm-4">
                                <div class="title-left h5 col-sm-12">
                                    Arquivos anexados
                                </div>
                                <?php foreach ($cotation->cotation_attachments as $anexo) : ?>
                                    <div class="col-sm-12">
                                        <a href="#"><?= $anexo->name_original ?></a>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row btn-partner-view-cot">
                    <button type="button" class="btn btn-success" id="btn-participar" style="padding-left:50px;padding-right:50px;margin-right: 15px;">Participar</button>
                    <?= $this->Html->link('Voltar', ['action' => 'index'], ['class' => 'btn btn-dark', 'id' => 'preto']); ?>
                </div>
            </div>

            <!-- TELA 2 - SERVIÇO -->
            <div id="tela2" class="ngproc-card-content" style="padding-left:25px">
                <div class="row">
                    <div id="nova" style="padding:10px 0 15px 10px">
                        <button type="button" class="btn btn-primary btn-sm" id="btn-abrir-modal-fornecedor" data-toggle="modal" data-target="#modal-fornecedor">Novo Fornecedor</button>
                    </div>
                    <div id="partner-view-service">
                        <div style='text-align:center; margin:40px 0 50px 0'>Cadastre ao menos um fornecedor para habilitar o envio da cotação</div>
                    </div>
                </div>
                <div class="row btn-partner-view-cot">
                    <?= $this->Html->link('Voltar', ['action' => 'index'], ['class' => 'btn btn-dark', 'id' => 'preto']); ?>
                </div>
            </div>

            <!-- TELA 3 - SERVIÇO -->
            <div id="tela3" class="ngproc-card-content" style="padding-left:25px">
                <div class="row">
                    <div id="nova" style="padding:10px 0 15px 10px">
                        <button type="button" class="btn btn-primary btn-sm" id="btn-novo-fornecedor">Novo Fornecedor</button>
                        <button type="button" class="btn btn-primary btn-sm display-none" id="btn-abrir-modal-fornecedor" data-toggle="modal" data-target="#modal-fornecedor">Novo Fornecedor</button>
                    </div>
                    <div id="partner-view-service">
                        <div id="tela-fornecedor">
                            <div>
                                <div class="content-left col-sm-8">
                                    <div class="title-left h5 col-sm-12">
                                        Dados da cotação
                                    </div>
                                    <div class="row">
                                        <div class="form-group has-feedback input-linha col-sm-6">
                                            <label for="title_servico">Título</label>
                                            <input type="text" class="form-control" id="title_servico"  value="<?= $cotation->title ?>" readonly="readonly">
                                        </div>
                                        <div class="form-group has-feedback input-linha col-sm-6">
                                            <label for="Categoria">Categoria</label>
                                            <input type="hidden" id="category-service" value="<?= $cotation->cotation_service->category ?>">
                                            <input type="text" class="form-control" id="Categoria"  value="<?= $cotation->cotation_service->getCategoryName() ?>" readonly="readonly">
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="form-group has-feedback input-linha col-sm-6">
                                            <label for="descricao-service">Descrição dos Itens da Cotação</label>
                                            <input type="text" class="form-control" id="descricao-service"  value="<?= $cotation->cotation_service->description; ?>" readonly="readonly">
                                        </div>
                                        <div class="form-group has-feedback input-linha col-sm-6">
                                            <label for="abrangencia">Abrangência da cotação</label>
                                            <input type="text" class="form-control" id="abrangencia"  value="<?= $cotation->coverage; ?>" readonly="readonly">
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="form-group has-feedback input-linha col-sm-6">
                                            <label for="tipo_cobranca">Tipo de cobrança</label>
                                            <input type="text" class="form-control" id="tipo_cobranca"  value="<?= $cotation->cotation_service->collection_type; ?>" readonly="readonly">
                                        </div>
                                        <div class="form-group has-feedback input-linha col-sm-6">
                                            <label for="name">Prazo para conclusão</label>
                                            <input type="text" class="form-control" id="deadline_data-servico"  value="<?= $cotation->deadline_date; ?>" readonly="readonly">
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="form-group has-feedback col-sm-6">
                                            <label for="exp-orcamento">Orçamento por <?= $cotation->cotation_service->collection_type ?> *</label>
                                            <input type="text" class="form-control dinheiro-real" id="exp-orcamento_serv"  placeholder="Expectativa: R$ <?= $this->Number->format($cotation->cotation_service->estimate, ['places' => 2, 'locale' => 'pt_BR']) ?>" required>
                                        </div>
                                        <div class="form-group has-feedback col-sm-6">
                                            <label for="exp_start_servico">Expectativa de início de serviço *</label>
                                            <input type="text" class="form-control date" id="exp_start_servico"  placeholder="Expectativa: <?= $cotation->cotation_service->expectation_start; ?>" required>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="form-group has-feedback col-sm-6">
                                            <label for="tempo_estimado">Tempo estimado para conclusão *</label>
                                            <input type="text" class="form-control" id="tempo_estimado_serv"  placeholder="Expectativa: <?= $cotation->cotation_service->service_time; ?>" required>
                                        </div>
                                    </div>
                                </div>
                                <div class="content-right  col-sm-4">
                                    <div class="title-left h5 col-sm-12">
                                        Arquivos anexados
                                    </div>
                                    <?php foreach ($cotation->cotation_attachments as $anexo) : ?>
                                        <div class="col-sm-12">
                                            <a href="#"><?= $anexo->name_original ?></a>
                                        </div>
                                    <?php endforeach; ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row btn-partner-view-cot">
                    <input type="submit" class="btn btn-primary" id="btn-salvar-continuar" value="Salvar e Continuar" style="margin-right: 15px;padding:6px 35px">
                    <input type="submit" class="btn btn-success" id="verde" value="Salvar e Concluir" style="margin-right: 15px;">
                    <?= $this->Html->link('Voltar', ['action' => 'index'], ['class' => 'btn btn-dark', 'id' => 'preto']); ?>
                </div>
            </div>
            <?php endif; ?>
            <!-- === FIM DE COTAÇÃO DE SERVIÇO === -->

            <!--
                ====================================================
                            COTAÇÃO DE PRODUTO
                ====================================================
            -->
            <?php if($cotation->type == 0) : ?>
            <!-- TELA 1 - PRODUTO -->
            <input type="hidden" id="qtd_itens_cotation" value="<?= $qtdCotationProdutosItens ?>" />
            <div id="tela1" class="ngproc-card-content" style="padding-left:25px">
                <div class="row">
                    <div id="partner-view-product">
                        <div>
                            <div class="content-left col-sm-8">
                                <div class="title-left h5 col-sm-12">
                                    Dados da cotação
                                </div>
                                <div class="row">
                                    <div class="form-group has-feedback input-linha col-sm-6">
                                        <label for="objetivo-cliente">Objetivo do cliente</label>
                                        <input type="text" class="form-control" value="<?php
                                            if ($cotation->type == 0) {
                                                switch ($cotation->objective) {
                                                    case '1':
                                                        echo "Reduzir custos";
                                                        break;
                                                    default:
                                                        echo "Itens de difícil localização";
                                                        break;
                                                }
                                            } ?>" readonly="readonly">
                                    </div>
                                    <div class="form-group has-feedback input-linha col-sm-6">
                                        <label>Buscando por</label>
                                        <input type="text" class="form-control" value="Produtos" readonly="readonly">
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="form-group has-feedback input-linha col-sm-6">
                                        <label>Quantidade de fornecedores</label>
                                        <input type="text" class="form-control" value="<?= "No máximo " . $cotation->provider_qtd ?>" readonly="readonly">
                                    </div>
                                    <div class="form-group has-feedback input-linha col-sm-6">
                                        <label>Expectativa de orçamento total</label>
                                        <input type="text" class="form-control" value="<?php
                                            if ($cotation->type == 0) {
                                                $orcamento = 0;
                                                foreach ($cotation->cotation_product->cotation_product_items as $item) {
                                                    $orcamento = $orcamento + ($item->quote * $item->quantity);
                                                }
                                                echo 'R$ ' . $this->Number->format($orcamento, ['places' => 2, 'locale' => 'pt_BR']);
                                            } ?>" readonly="readonly">
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="form-group has-feedback input-linha col-sm-6">
                                        <label >Prazo para conclusão</label>
                                        <input type="text" class="form-control" value="<?= $cotation->deadline_date; ?>" readonly="readonly">
                                    </div>
                                    <div class="form-group has-feedback input-linha col-sm-6">
                                        <label>Abrangência da cotação</label>
                                        <input type="text" class="form-control" value="<?= $cotation->coverage; ?>" readonly="readonly">
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="form-group has-feedback input-linha col-sm-6">
                                        <label>CEP de Entrega</label>
                                        <input type="text" class="form-control" value="<?= empty($cotation->address_zipcode) ? 'Não informado' : $cotation->address_zipcode ?>" readonly="readonly">
                                    </div>
                                </div>
                            </div>
                            <div class="content-right float-right col-sm-4">
                                <div class="title-left h5 col-sm-12">
                                    Arquivos anexados
                                </div>
                                <?php foreach ($cotation->cotation_attachments as $anexo) : ?>
                                    <div class="col-sm-12 anexos-listados">
                                        <a href="<?= $this->Url->build("/uploads/cotations/{$anexo->name}") ?>" download="<?= $anexo->name_original ?>"><?= $anexo->name_original ?></a>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        </div>
                        <div id="tabela-cotacoes" class="table-responsive" style="margin-left: 15px;">
                            <table id="cotacoes" class="table table-bordered table-responsive">
                                <div class="scrollmenu">
                                    <thead>
                                        <tr>
                                            <th>Nome</th>
                                            <th>Categoria</th>
                                            <th>Quantidade</th>
                                            <th>Orçamento Unitário</th>
                                            <th>Fabricante</th>
                                            <th>Modelo</th>
                                            <!-- <th>SKU</th> -->
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($cotation->cotation_product->cotation_product_items as $item) : ?>
                                            <tr>
                                                <td><?= $item->product->item_name ?></td>
                                                <td style="text-align:center"><?= $item->product->getCategoryName() ?></td>
                                                <td style="text-align:center" class="lp-s-1"><?= $item->quantity ?></td>
                                                <td style="text-align:center"><?= "R$ " . $this->Number->format($item->quote, ['places' => 2, 'locale' => 'pt_BR']); ?></td>
                                                <td style="text-align:center"><?= $item->product->manufacturer ?></td>
                                                <td style="text-align:center"><?= $item->product->model ?></td>
                                                <!-- <td style="text-align:center"><?= $item->product->sku ?></td> -->
                                            </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                </div>
                            </table>
                        </div>
                    </div>
                </div>
                <div class="row btn-partner-view-cot">
                    <button type="button" class="btn btn-success" id="btn-participar" style="padding-left:50px;padding-right:50px;margin-right: 15px;">Participar</button>
                    <?= $this->Html->link('Voltar', ['action' => 'index'], ['class' => 'btn btn-dark', 'id' => 'preto']); ?>
                </div>
            </div>

            <!-- TELA 2 - PRODUTO -->
            <div id="tela2" class="ngproc-card-content" style="padding-left:25px">
                <div class="row">
                <div id="nova" style="padding:10px 0 15px 10px">
                        <button type="button" class="btn btn-primary btn-sm" id="btn-abrir-modal-fornecedor" data-toggle="modal" data-target="#modal-fornecedor">Novo Fornecedor</button>
                    </div>
                    <div id="partner-view-product">
                        <div style='text-align:center; margin:40px 0 50px 0'>Cadastre ao menos um fornecedor para habilitar o envio da cotação</div>
                    </div>
                </div>
                <div class="row btn-partner-view-cot">
                    <?= $this->Html->link('Voltar', ['action' => 'index'], ['class' => 'btn btn-dark', 'id' => 'preto']); ?>
                </div>
            </div>

            <!-- TELA 3 - PRODUTO -->
            <div id="tela3" class="ngproc-card-content" style="padding-left:25px">
                <div class="row">
                    <div id="nova" style="padding:10px 0 15px 10px">
                        <button type="button" class="btn btn-primary btn-sm" id="btn-novo-fornecedor">Novo Fornecedor</button>
                        <button type="button" class="btn btn-primary btn-sm display-none" id="btn-abrir-modal-fornecedor" data-toggle="modal" data-target="#modal-fornecedor">Novo Fornecedor</button>
                    </div>
                    <div class="col-sm-12" id="result-tela3"></div>
                    <div id="partner-view-product">
                        <div id="tela-fornecedor">
                            <div>
                                <div class="content-left col-sm-8">
                                    <div class="title-left h5 col-sm-12">
                                        Dados da cotação
                                    </div>
                                    <div class="row">
                                        <div class="form-group has-feedback input-linha col-sm-6">
                                            <label>Objetivo do Cliente</label>
                                            <input type="hidden" id="objetivo-cliente" value="<?=$cotation->objective?>">
                                            <input type="text" class="form-control"  value="<?php
                                                if ($cotation->type == 0) {
                                                    switch ($cotation->objective) {
                                                        case '1':
                                                            echo "Reduzir custos";
                                                            break;

                                                        default:
                                                            echo "Itens de difícil localização";
                                                            break;
                                                    }
                                                } ?>" readonly="readonly">
                                        </div>
                                        <div class="form-group has-feedback input-linha col-sm-6">
                                            <label for="buscando-produto">Buscando por</label>
                                            <input type="text" class="form-control" id="buscando-produto" value="Produtos" readonly="readonly">
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="form-group has-feedback input-linha col-sm-6">
                                            <label>Quantidade de Fornecedores</label>
                                            <input type="hidden" id="qtd-fornecedores" value="<?=$cotation->provider_qtd ?>">
                                            <input type="text" class="form-control" value="<?= "No máximo " . $cotation->provider_qtd ?>" readonly="readonly">
                                        </div>
                                        <div class="form-group has-feedback input-linha col-sm-6">
                                            <label for="exp-orcamento">Expectativa de Orçamento do Cliente</label>
                                            <input type="text" class="form-control" id="exp-orcamento" value="<?php
                                            if ($cotation->type == 0) {
                                                $orcamento = 0;
                                                foreach ($cotation->cotation_product->cotation_product_items as $item) {
                                                    $orcamento = $orcamento + ($item->quote * $item->quantity);
                                                }
                                                echo 'R$ ' . $this->Number->format($orcamento, ['places' => 2, 'locale' => 'pt_BR']);
                                            } ?>" readonly="readonly">
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="form-group has-feedback input-linha col-sm-6">
                                            <label>Prazo para Conclusão</label>
                                            <input type="text" class="form-control" id="prazo-conclusao"  value="<?= $cotation->deadline_date; ?>" readonly="readonly">
                                        </div>
                                        <div class="form-group has-feedback input-linha col-sm-6">
                                            <label>Abrangência da Cotação</label>
                                            <input type="text" class="form-control" id="abrangencia"  value="<?= $cotation->coverage; ?>" readonly="readonly">
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="form-group has-feedback input-linha col-sm-6">
                                            <label>CEP de Entrega</label>
                                            <input type="text" class="form-control" value="<?= empty($cotation->address_zipcode) ? 'Não informado' : $cotation->address_zipcode ?>" readonly="readonly">
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="form-group has-feedback col-sm-6">
                                            <label for="prazo-entrega">Prazo de Entrega (dias) *</label>
                                            <input type="number" class="form-control" id="prazo-entrega"  min="1" placeholder="Digite apenas números" required style="padding-right: 5px">
                                        </div>
                                        <div class="form-group has-feedback col-sm-6">
                                            <label for="cost_freight">Valor do Frete *
                                            <a  href="#"
                                                id="mytooltip"
                                                data-toggle="tooltip"
                                                title="Caso pretenda oferecer frete grátis, deixe este campo vazio."
                                                data-placement="top"
                                                style="margin-left:10px">
                                                <?= $this->Html->image('icons/info.png', ["width" => "16px", 'heigth' => "16px"]) ?>
                                            </a>
                                            </label>
                                            <input type="text" class="form-control dinheiro-real" id="cost_freight"  placeholder="R$ 0,00">
                                        </div>
                                    </div>
                                </div>
                                <!-- <div class="content-right float-right col-sm-4">
                                    <div class="title-left h5 col-sm-12">
                                        Arquivos Anexados
                                    </div>
                                    <?php foreach ($cotation->cotation_attachments as $anexo) : ?>
                                        <div class="col-sm-12">
                                            <a href="<?= $this->Url->build("/uploads/cotations/{$anexo->name}") ?>" download="<?= $anexo->name_original ?>"><?= $anexo->name_original ?></a>
                                        </div>
                                    <?php endforeach; ?>
                                </div> -->
                                <div class="col-sm-6" style="margin-top: 20px">
                                    <div id="titulo2" class="form-group has-feedback">

                                        <label style="margin-bottom: 10px">
                                            Anexar arquivos úteis à cotação do fornecedor *
                                            <a  href="#"
                                                id="mytooltip"
                                                data-toggle="tooltip"
                                                title="Caso não anexe arquivos relacionados a este fornecedor, você precisará preencher o campo link, item por item."
                                                data-placement="bottom"
                                                style="margin-left:10px">
                                                <?= $this->Html->image('icons/info.png', ["width" => "16px", 'heigth' => "16px"]) ?>
                                            </a>
                                        </label>
                                        <!-- ANEXOS DE AQUIVOS ÚTEIS PARA A COTAÇÃO -->
                                        <div class="box-anexo-new-quotation btn btn-sm float-left form-group has-feedback">
                                            <form id="form-anexo-uteis-fornecedor" enctype="multipart/form-data">
                                                <input type="file" name="anexo[]" id="anexos-file" multiple>
                                                <input type="text" name="cotation-id" id="cotation-reference-anexo" style="display:none">
                                                <input type="text" name="provider-id" id="provider-reference-anexo" style="display:none">
                                                <input type="submit" id="btn-anexo-uteis-fornecedor" style="display:none">
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div id="tabela-cotacoes" class="table-responsive" style="margin-left: 15px;">
                                <?php if($qtdCotationProdutosItens >= 15) : ?>
                                <div class="row box-btn-estimate" style="margin: 0 0 40px 0">
                                    <button type="button" class="btn btn-calcular-orcamento" style="float:left">Calcular Orçamento</button>
                                    <div class="col-md-3">
                                        <input type="text" class="form-control mostrar-orcamento" id="" readonly="readonly" placeholder="R$ 0,00">
                                    </div>
                                </div>
                                <?php endif; ?>
                                <table id="cotacoes" class="table table-bordered table-responsive">
                                    <div class="scrollmenu">
                                        <thead>
                                            <tr>
                                                <!-- <th><?=$this->Html->image("icons/block.png", ["width" => "16px", 'heigth' => "16px"])?></th> -->
                                                <th class="item-cotation-send">
                                                    <input type="checkbox" class="checkbox" id="check-all" checked>
                                                    <label for="check-all"></label>
                                                </th>
                                                <th>Nome</th>
                                                <th>Categoria</th>
                                                <th>Quantidade *</th>
                                                <th>Orçamento unitário *</th>
                                                <th>Fabricante</th>
                                                <th>Modelo</th>
                                                <th>Link *
                                                <a  href="#"
                                                    id="mytooltip"
                                                    data-toggle="tooltip"
                                                    title="Caso decida não preencher o campo link dos itens, obrigatoriamente precisará anexar ao menos um arquivo relacionado a este fornecedor."
                                                    data-placement="bottom"
                                                    style="margin-left:10px">
                                                    <?= $this->Html->image('icons/info.png', ["width" => "16px", 'heigth' => "16px"]) ?>
                                                </a>
                                                </th>
                                                <!-- <th>Fornecedor</th> -->
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php
                                                $idItem = 0;
                                                foreach ($cotation->cotation_product->cotation_product_items as $item) : ?>
                                                <input type="hidden" id="<?= 'productId' . $idItem ?>" value="<?= $item->product->id ?>" />
                                                <input type="hidden" id="<?= 'itemName' . $idItem ?>" value="<?= $item->product->item_name ?>" />
                                                <input type="hidden" id="<?= 'categoryItemProd' . $idItem ?>" value="<?= $item->product->category_item_prod ?>" />
                                                <input type="hidden" id="<?= 'quantity-default' . $idItem ?>" value="<?=  $item->quantity ?>" />
                                                <input type="hidden" id="<?= 'quote-default' . $idItem ?>" value="<?="R$ " . $this->Number->format($item->quote, ['places' => 2, 'locale' => 'pt_BR']); ?>" />
                                                <tr>
                                                    <td class="item-cotation-send">
                                                        <input type="checkbox" id="<?=$idItem?>" class="checkbox check<?=$idItem?> checkbox-habilitar-item">
                                                        <label for="<?=$idItem?>"></label>
                                                    </td>
                                                    <td><?= $item->product->item_name ?></td>
                                                    <td><?= $item->product->getCategoryName() ?></td>
                                                    <!-- <td><select class="quantidade-item" id="" name="product[<?= $item->id ?>][quantity]">
                                                        <?php
                                                        for ($i = 0; $i <= $item->quantity; $i++) :
                                                            echo "<option value=\"{$i}\">{$i}</option>";
                                                        endfor;
                                                        ?>
                                                            </select>
                                                        </td> -->
                                                    <td class="lp-s-1"><input required class="form-control quantity" type="number" name="quantity" id="<?= 'quantity' . $idItem ?>" value="<?= $item->quantity ?>" placeholder="Máximo <?= $item->quantity ?>" min="1" max="<?= $item->quantity ?>"></td>
                                                    <td class="lp-s-0"><input required class="dinheiro-real form-control quote" type="text" id="<?= 'quote' . $idItem ?>" value="<?="R$ " . $this->Number->format($item->quote, ['places' => 2, 'locale' => 'pt_BR']); ?>" placeholder="<?="R$ 0,00" //. $this->Number->format($item->quote, ['places' => 2, 'locale' => 'pt_BR']); ?>" step="0.01"></td>
                                                    <td class="lp-s-1" style="max-width:100px"><?= $item->product->manufacturer ?></td>
                                                    <td class="lp-s-1" style="max-width:100px; max-height:50px; overflow-x:auto; word-wrap: break-word;"><?= $item->product->model ?></td>
                                                    <!-- <td class="lp-s-1"><?= $item->product->sku ?></td> -->
                                                    <td><input class="form-control" style="max-width:300px" type="text" id="<?= 'link' . $idItem ?>" placeholder="Link do item"></td>
                                                    <input class="form-control" type="hidden" id="<?= 'manufacturer' . $idItem ?>" value="<?= $item->product->manufacturer ?>" placeholder="<?= $item->product->manufacturer ?>">
                                                    <input class="form-control" type="hidden" id="<?= 'model' . $idItem ?>" value="<?= $item->product->model ?>" placeholder="<?= $item->product->model ?>">
                                                    <!-- <input class="form-control" type="hidden" id="<?= 'sku' . $idItem ?>" value="<?= $item->product->sku ?>" placeholder="<?= $item->product->sku ?>"> -->
                                                    <!-- <td><label>
                                                                <select id="item-provider" required="required" name="product[<?= $item->id ?>][provider_id]">
                                                                    <option value="" selected disabled>Opções de fornecedores</option>
                                                                    <! -- <option value=" newProvider">Cadastrar novo Fornecedor</option> -- >
                                                                </select>
                                                            </label>
                                                        </td> -->
                                                </tr>
                                            <?php
                                                $idItem++;
                                            endforeach; ?>
                                        </tbody>
                                    </div>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row box-btn-estimate" style="margin: 20px 0 60px 0">
                    <button type="button" class="btn btn-calcular-orcamento" style="float:left">Calcular Orçamento</button>
                    <div class="col-md-3">
                        <input type="text" class="form-control mostrar-orcamento" id="" readonly="readonly" placeholder="R$ 0,00">
                    </div>
                </div>
                <div class="row btn-partner-view-cot" style="margin-left:0">
                    <input type="submit" class="btn btn-primary" id="btn-salvar-continuar" value="Salvar e Continuar" style="margin-right: 15px;padding:6px 35px">
                    <input type="submit" class="btn btn-success" id="verde" value="Salvar e Concluir" style="margin-right: 15px;">
                    <!-- <input type="button" class="btn btn-dark" id="preto" value="Voltar" style="margin-right: 15px;"> -->
                    <?= $this->Html->link('Voltar', ['action' => 'index'], ['class' => 'btn btn-dark', 'id' => 'preto']); ?>
                </div>
            </div>
            <?php endif; ?>
            <!-- === FIM DE COTAÇÃO DE PRODUTO === -->
        </div>
        <!-- <?= $this->Form->end() ?> -->
        <div class="error"></div>
    </div>
</div>
<div class="modal fade" id="modal-fornecedor" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close" id="btn-close-modal"><span aria-hidden="true">&times;</span></button>
                <h3 class="modal-title">Adicionar fornecedor</h3>
            </div>
                <div class="modal-body">
                    <div class="row text-center">
                        <div class="col-sm-12 col-md-12" id="alert">

                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-12">
                            <input type="hidden" id="id-provider">
                            <div class="form-group has-feedback col-sm-6">
                                <label for="cnpj-provider">CNPJ *</label>
                                <input type="text" class="form-control cnpj" id="cnpj-provider" onkeyup="FormataCnpj(this,event)" onblur="if(!validarCNPJ(this.value)){alertarModal('CNPJ Informado é inválido','danger',2300); this.value=''; this.focus();}" required>
                            </div>
                            <div class="form-group has-feedback col-sm-6">
                                <label for="nome-provider">Razão social ou Nome fantasia *</label>
                                <input type="text" class="form-control" id="nome-provider" required>
                            </div>
                        </div>
                        <div class="col-sm-12">
                            <div class="form-group has-feedback col-sm-6">
                                <label for="site-provider">Site</label>
                                <input type="text" class="form-control" id="site-provider"  />
                            </div>
                            <div class="form-group has-feedback col-sm-6">
                                <label for="tel-provider">Telefone</label>
                                <input type="text" class="form-control cellphone_with_ddd" id="tel-provider"  pattern=".{14,}" />
                            </div>
                        </div>
                        <div class="col-sm-12">
                            <div class="form-group has-feedback col-sm-6">
                                <label for="cep-provider">CEP *</label>
                                <input type="text" class="form-control cep" id="cep-provider" required>
                            </div>
                            <div class="form-group has-feedback col-sm-6">
                                <label for="rua-provider">Rua *</label>
                                <input type="text" class="form-control" id="rua-provider" required>
                            </div>
                        </div>
                        <div class="col-sm-12">
                            <div class="form-group has-feedback col-sm-6">
                                <label for="numero-provider">Número *</label>
                                <input type="text" class="form-control" id="numero-provider" required>
                            </div>
                            <div class="form-group has-feedback col-sm-6">
                                <label for="complemento-provider">Complemento</label>
                                <input type="text" class="form-control" id="complemento-provider">
                            </div>
                        </div>
                        <div class="col-sm-12">
                            <div class="form-group has-feedback col-sm-6">
                                <label for="bairro-provider">Bairro *</label>
                                <input type="text" class="form-control" id="bairro-provider" required>
                            </div>
                            <div class="form-group has-feedback col-sm-6">
                                <label for="cidade-provider">Cidade *</label>
                                <input type="text" class="form-control" id="cidade-provider" required>
                            </div>
                        </div>
                        <div class="col-sm-12">
                            <div class="form-group has-feedback col-sm-6">
                                <label for="uf-provider">UF *</label>
                                <input type="text" class="form-control" id="uf-provider" required>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal" id="fechar-modal-fornecedor">Fechar</button>
                    <button type="button" class="btn btn-primary" id="cadastro-provider">Cadastrar</button>
                </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div>

<!-- /.modal -->

<!-- <div class="modal fade" id="modal-voltar" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-body">
                <div>Você tem certeza que deseja voltar?</div>
                <div>Se você voltar sem salvar, seus dados serão pedidos.</div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal" id="fechar-modal-voltar">Fechar</button>
            </div>
        </div>< !-- /.modal-content -- >
    </div>< !-- /.modal-dialog -- >
</div>< !-- /.modal -- > -->


<!-- jQuery 3 -->
<script src="<?= $this->Url->build('bower_components/jquery/dist/jquery.min.js') ?>"></script>
<!-- jQuery Mask Plugin -->
<script src="<?= $this->Url->build('bower_components/jQuery-Mask-Plugin-master/dist/jquery.mask.min.js') ?>"></script>
<!-- Sweetalert -->
<script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
<!-- jquery-maskmoney -->
<script src="<?= $this->Url->build('bower_components/plentz-jquery-maskmoney-cdbeeac/dist/jquery.maskMoney.js') ?>"></script>
<script>
/**
 * FALTA IMPLEMENTAR
 * - bloquear botões de salvar ao ter enviados todos os fornecedores ou já ter salvo o envio daquele fornecedor
 */



// Abrir tela inicial de participação da cotação
$("#tela3").hide();
$("#tela2").hide();
$("#tela1").show();

//Transitação entre as telas da cotação
// De "Participar" para "Adicionar Fornecedor"
$("#btn-participar").click(() => {
    if($('#dados-bancarios-incompletos').val() == 0){
        $("#tela1").hide();
        $("#tela2").show();
        $("#btn-abrir-modal-fornecedor").click();
    }else{
        swal({
        title: "Dados Bancários",
        text: `Seus dados bancários estão incompletos.
        Para participar de uma cotação, esses dados devem ser preenchidos corretamente.`,
        icon: "info",
        buttons: {
            cancel: "Ignorar",
            // catch: {
            // text: "Ir preencher",
            // value: "catch",
            // },
            defeat: {
                text: "Ir preencher",
                value: "defeat",
            }
        },
        })
        .then((value) => {
        switch (value) {

            case "defeat":
                window.location.href = '<?= $this->Url->build(['prefix' => 'partner', 'controller' => 'Profile', 'action' => 'index'])?>';
            break;
            // case "catch":
            //     swal(`Clique em #2: ${value}`);
            // break;

            // default:
            // swal("Eu sou o padrão");
        }
        });
    }
});
</script>

<script>
$(document).ready(function(){
    $('#mytooltip').tooltip();
});
</script>

<script>
var qtdItensNaoCheckeds = 0;
let LIMITE = 1;

if( $('#check-all').prop('checked') ){
    $('.checkbox-habilitar-item').prop('checked', 'checked');
}
// alert('QTD DE NÃO CHECADOS: ' + qtdItensNaoCheckeds)
$('#check-all').on('click',function() {
    if(this.checked){
        this.checked = true;
        qtdItensNaoCheckeds = $('#qtd_itens_cotation').val();
        $('.checkbox-habilitar-item').prop('checked', false)
        $('.checkbox-habilitar-item').click();
    }else{
        if( qtdItensNaoCheckeds >= ( $('#qtd_itens_cotation').val() - LIMITE) ){
            this.checked = true;
            swal({
                title: "Aviso",
                text: "Você precisa de ao menos um item cotado para enviar a cotação.",
                icon: "info",
                button: "Está bem"
            });
        }else{
            this.checked = false;
            qtdItensNaoCheckeds = (LIMITE - 1);
            $('.checkbox-habilitar-item').prop('checked', true);
            $('.checkbox-habilitar-item').click();
        }
    }
});

$('.checkbox-habilitar-item').on('click',function() {
    if(this.checked){
        qtdItensNaoCheckeds--;
        let item_id = $(this).attr('id');
        let elemento = '#quantity'+item_id;
        let elemento_default = '#quantity-default'+item_id;
        $(elemento).val($(elemento_default).val());
        $(elemento).prop("disabled", false);
        elemento = '#quote'+item_id;
        elemento_default = '#quote-default'+item_id;
        $(elemento).val($(elemento_default).val());
        $(elemento).prop("disabled", false);
        if(qtdItensNaoCheckeds == 0) $('#check-all').prop('checked', true);
        // alert('QTD DE ITENS: ' + $('#qtd_itens_cotation').val())
        // alert('Agora ficaram ' + qtdItensNaoCheckeds + ' desmarcados')
    }else{
        if( qtdItensNaoCheckeds >= ( $('#qtd_itens_cotation').val() - LIMITE) ){
            this.checked = true;
            swal({
                title: "Aviso",
                text: "Você precisa de ao menos um item cotado para enviar a cotação.",
                icon: "info",
                button: "Está bem"
            })
        }else {
            qtdItensNaoCheckeds++;
            let item_id = $(this).attr('id');
            let elemento = '#quantity'+item_id;
            $(elemento).val('0');
            $(elemento).prop("disabled", true);
            elemento = '#quote'+item_id;
            $(elemento).val('');
            $(elemento).prop("disabled", true);
            $('#check-all').prop('checked', false);
            // alert('QTD DE ITENS: ' + $('#qtd_itens_cotation').val())
            // alert('Ficaram ' + qtdItensNaoCheckeds + ' desmarcados')
        }
    }
});
</script>

<script>
$(document).ready(function() {

    $(".dinheiro-real").maskMoney({
        prefix: "R$ ",
        decimal: ",",
        thousands: ".",
        affixesStay: true,
    });

    $('.cep').mask('00000-000');

    $('.cnpj').mask('00.000.000/0000-00', {
        reverse: true
    });

    $('.cellphone_with_ddd').mask('(00) 00000-0000');

    $(".cellphone_with_ddd").blur(function(event) {
        if ($(this).val().length == 15) {
            $(".cellphone_with_ddd").mask("(00) 00000-0000");
        } else {
            $(".cellphone_with_ddd").mask("(00) 0000-0000");
        }
    });

    $('.date').mask('00/00/0000');

});
</script>

<script>
//VALIDAÇÃO DO CNPJ NA MODAL DE ADD FORNECEDOR
function alertarModal(mensagem, cor, tempo) {
    $('#alert').fadeIn();

    alerta = `<div class="alert alert-` + cor + `" role="alert">
                        ` + mensagem + `
                </div>`;
    $('#alert').html(alerta);
    $('#alert').fadeOut(tempo);
}

function validarCNPJ(cnpj) {

    cnpj = cnpj.replace(/[^\d]+/g, '');

    if (cnpj == '') return false;

    if (cnpj.length != 14)
        return false;

    // Elimina CNPJs invalidos conhecidos
    if (cnpj == "00000000000000" ||
        cnpj == "11111111111111" ||
        cnpj == "22222222222222" ||
        cnpj == "33333333333333" ||
        cnpj == "44444444444444" ||
        cnpj == "55555555555555" ||
        cnpj == "66666666666666" ||
        cnpj == "77777777777777" ||
        cnpj == "88888888888888" ||
        cnpj == "99999999999999")
        return false;

    // Valida DVs
    tamanho = cnpj.length - 2
    numeros = cnpj.substring(0, tamanho);
    digitos = cnpj.substring(tamanho);
    soma = 0;
    pos = tamanho - 7;
    for (i = tamanho; i >= 1; i--) {
        soma += numeros.charAt(tamanho - i) * pos--;
        if (pos < 2)
            pos = 9;
    }
    resultado = soma % 11 < 2 ? 0 : 11 - soma % 11;
    if (resultado != digitos.charAt(0))
        return false;

    tamanho = tamanho + 1;
    numeros = cnpj.substring(0, tamanho);
    soma = 0;
    pos = tamanho - 7;
    for (i = tamanho; i >= 1; i--) {
        soma += numeros.charAt(tamanho - i) * pos--;
        if (pos < 2)
            pos = 9;
    }
    resultado = soma % 11 < 2 ? 0 : 11 - soma % 11;
    if (resultado != digitos.charAt(1))
        return false;

    return true;

}
</script>

<script>
//var telaFornecedorDefault = document.getElementById('tela-fornecedor').innerHTML;
var contadorTelaFornecedor = -1;

function validarDadosCotacaoPorFornecedor() {
    if($('#type-cotation').val() == 0){
        if ($('#prazo-entrega').val() == "") {
            $("#prazo-entrega").focus();
            return false;
        // } else if ($('#cost_freight').val() == "") {
        //     $("#cost_freight").focus();
        //     return false;
        } else {
            // let todosItensChecked = -1;
            // let cont = 0;
            for (var i = 0; i < $('#qtd_itens_cotation').val(); i++) {
                //cont = i;
                let classe = ".check" + i;
                var elementoQuantity = "#quantity" + i,
                    elementoQuote = "#quote" + i;
                    // elementoManufacturer = "#manufacturer" + i,
                    // elementoModel = "#model" + i,
                    elementoLink = "#link" + i;
                if($(classe).is(':checked')){
                    if ($(elementoQuantity).val() == "" || $(elementoQuantity).val() == "0") {
                        $(elementoQuantity).focus();
                        return false;
                    } else if ($(elementoQuote).val() == "") {
                        $(elementoQuote).focus();
                        return false;
                    // } else if ($(elementoManufacturer).val() == "") {
                    //     $(elementoManufacturer).focus();
                    //     return false;
                    // } else if ($(elementoModel).val() == "") {
                    //     $(elementoModel).focus();
                    //     return false;
                    } else if ($('#anexos-file').val() == "" && $(elementoLink).val() == ""){
                            $(elementoLink).focus();
                            return false;
                    } else if ($(elementoLink).val() != "") {
                        var val = $(elementoLink).val()
                        if (! val.includes('http')) {
                            $(elementoLink).val('http://' + $(elementoLink).val() );
                        }
                        if ( !is_url($(elementoLink).val())) {
                            return false
                        }
                        return true
                    } else if (i + 1 == $('#qtd_itens_cotation').val()) {
                        return true;
                    }
                }
            }
        }
    }else if($('#type-cotation').val() == 1){
        if ($("#exp-orcamento_serv").val() == "") {
            $("#exp-orcamento_serv").focus();
            return false;
        } else if ($("#exp_start_servico").val() == "") {
            $("#exp_start_servico").focus();
            return false;
        } else if ($("#tempo_estimado_serv").val() == "") {
            $("#tempo_estimado_serv").focus();
            return false;
        } else {
            return true;
        }
    }
}

function is_url(str)
{
  regexp =  /^(?:(?:https?|ftp):\/\/)?(?:(?!(?:10|127)(?:\.\d{1,3}){3})(?!(?:169\.254|192\.168)(?:\.\d{1,3}){2})(?!172\.(?:1[6-9]|2\d|3[0-1])(?:\.\d{1,3}){2})(?:[1-9]\d?|1\d\d|2[01]\d|22[0-3])(?:\.(?:1?\d{1,2}|2[0-4]\d|25[0-5])){2}(?:\.(?:[1-9]\d?|1\d\d|2[0-4]\d|25[0-4]))|(?:(?:[a-z\u00a1-\uffff0-9]-*)*[a-z\u00a1-\uffff0-9]+)(?:\.(?:[a-z\u00a1-\uffff0-9]-*)*[a-z\u00a1-\uffff0-9]+)*(?:\.(?:[a-z\u00a1-\uffff]{2,})))(?::\d{2,5})?(?:\/\S*)?$/;
        if (regexp.test(str))
        {
          return true;
        }
        else
        {
          return false;
        }
}

function limparCamposModalFornecedor() {
    $('#cnpj-provider').val('');
    $('#nome-provider').val('');
    $('#site-provider').val('');
    $('#tel-provider').val('');
    $('#cep-provider').val('');
    $("#rua-provider").val('');
    $("#numero-provider").val('');
    $("#complemento-provider").val('');
    $("#bairro-provider").val('');
    $("#cidade-provider").val('');
    $("#uf-provider").val('');
}

function limparCamposCotacao() {
    if($('#type-cotation').val() == 0){
        $('#prazo-entrega').val("");
        $('#cost_freight').val("");
        $('.mostrar-orcamento').val("");
        $('#anexos-file').val("");
        $('#prazo-entrega').focus();

        for (var i = 0; i < $('#qtd_itens_cotation').val(); i++) {
            var elementoQuantity = "#quantity" + i,
                elementoQuote = "#quote" + i;
                elementoLink = "#link" + i;
                // elementoManufacturer = "#manufacturer" + i,
                // elementoModel = "#model" + i,
                // elementoSku = "#sku" + i;
            let elemento = "#quantity-default" + i;
            $(elementoQuantity).val($(elemento).val());
            elemento = "#quote-default" + i;
            $(elementoQuote).val($(elemento).val());
            $(elementoLink).val("");

            // $(elementoManufacturer).val("");
            // $(elementoModel).val("");
            // $(elementoSku).val("");
        }
    }else if($('#type-cotation').val() == 1){
        $('#exp-orcamento_serv').val("");
        $('#exp_start_servico').val("");
        $('#tempo_estimado_serv').val("");
        $('#exp-orcamento_serv').focus();
    }
}

function formatarNomeFornecedor(name){
    name = name.split(" ");
    let aux = name[name.length -1].split("");
    name = name[0] + " " + aux[0] + ".";
    return name;
}

$('#btn-novo-fornecedor').click(()=>{
    if(validarDadosCotacaoPorFornecedor()){
        if(contadorTelaFornecedor >= ($('#provider_qtd').val() - 1)){
            swal({
                title: "Sinto muito",
                text: "Não é possível cadastrar mais fornecedores para esta cotação.",
                icon: "error",
                button: "Está bem"
            });
        }else{
            $("#btn-abrir-modal-fornecedor").click();
        }
    }else{
        swal({
            title: "Por favor",
            text: "Para continuar, preencha todos os campos obrigatórios corretamente.",
            icon: "info",
            button: "Está bem"
        }).then(value => {
            validarDadosCotacaoPorFornecedor();
        });
    }
});

//===========================================================
//          PREPARANDO DADOS DO FORNECEDOR
//===========================================================
var controlProvider = [];
function temEsteProvider(cnpj) {
    if (controlProvider.length == 0) {
        return false
    }
    for (let i = 0; i < controlProvider.length; i++) {
        if(controlProvider[i].cnpj == cnpj){
            i = controlProvider.length;
            return true;
        }
    }
    return false;
}
$('#cadastro-provider').click(function() {

    let provider = {
        id: $('#id-provider').val(),
        cnpj: $('#cnpj-provider').val(),
        name: $('#nome-provider').val(),
        site: $('#site-provider').val(),
        telephone: $('#tel-provider').val(),
        address_zipcode: $('#cep-provider').val(),
        address_street: $("#rua-provider").val(),
        address_number: $("#numero-provider").val(),
        address_complement: $("#complemento-provider").val(),
        address_neighborhood: $("#bairro-provider").val(),
        address_city: $("#cidade-provider").val(),
        address_uf: $("#uf-provider").val(),
    };

    //Restringindo o usuário de inserir o mesmo fornecedor à cotação
    if(temEsteProvider(provider.cnpj)){
        swal({
            title: "Ops",
            text: "Fornecedor já está alocado na cotação, por favor tente outro",
            icon: "info"
        })
    }else{
        let p = $.post('<?= $this->Url->build(['action' => 'addProvider']) ?>', provider);
        carregarLoad(true);

        $('#fechar-modal-fornecedor').click();

        p.done(function response(data) {
            carregarLoad(false);
            if (data.result == 'success') {
                controlProvider.push(provider);
                limparCamposModalFornecedor();
                contadorTelaFornecedor++;
                swal("Fornecedor adicionado com sucesso", {
                    icon: "success"
                })
                .then(value => {

                    $('#id-provider').val(data.idProvider);
                    $("#tela2").hide();
                    $("#tela3").show();
                    $("#nav_tabs_ul").append('<li id="aba-f' + contadorTelaFornecedor + '"><input type="radio" name="tabs" class="rd_tabs" id="tab' + contadorTelaFornecedor + '" checked><label class="active" for="tab' + contadorTelaFornecedor + '">' + formatarNomeFornecedor(provider.name) + '</label></li>');
                    limparCamposCotacao();
                    // Desabilitando a aba
                    let i = contadorTelaFornecedor -1;
                    let elemento = "#aba-f" + i + " input";
                    $(elemento).prop("disabled", true);

                    // desabilitando botão para evitar erro de usuário clicar e salvar dado 2 vezes
                    $('#btn-salvar-continuar').prop("disabled",false);
                    $('#verde').prop("disabled",false);
                });
            } else {
                //$(".error").html(data);
                let texto = "Não foi possível cadastrar este fornecedor"
                if(data.result == 'suspenso'){
                    texto = "Este cnpj está suspenso, não pode ser utilizado."
                }
                swal({
                    title: "Ops!",
                    text: texto,
                    icon: "error"
                }).then(value => {
                    limparCamposModalFornecedor();
                });
            }
        });
    }
});
</script>

<script>
//===========================================================
//PREPARANDO DADOS DO ENVIO DA COTAÇÃO
//===========================================================
function prepararCotation(){
    let cotation = {
            id: $('#cotation_enviada').val() == "" ? null : parseInt($('#cotation_enviada').val()),
            title: $('#title-cotation').val(),
            type: $('#type-cotation').val(),
            provider_qtd: $('#qtd-fornecedores').val(),
            objective: $('#objetivo-cliente').val(),
            deadline_date: $('#prazo-conclusao').val(),
            status: "0",
            created: "",
            coverage: $('#abrangencia').val(),
            user_id: "",
            main_cotation_id: $('#main_cotation_id').val(),
            modified: "",
            cotation_product: "",
            cotation_service: "",
            cotation_providers: "",
            cotation_attachments: ""
        }
    return cotation;
}

function prepararCotationProduct(){
    //preparando cotação de produto
    let cotation_product = {
        //id: "",
        estimate: "",
        cotation_id: "",
        cotation_product_items: []
    }

    for (let i = 0; i < $('#qtd_itens_cotation').val(); i++) {
        let elementoQuantity = "#quantity" + i,
            elementoQuote = "#quote" + i,
            elementoProductId = "#productId" + i,
            elementoItemName = "#itemName" + i,
            elementoCategory = "#categoryItemProd" + i,
            elementoManufacturer = "#manufacturer" + i,
            elementoModel = "#model" + i,
            elementoLink = "#link" + i;

        let item = {
            cotation_product_id: '',
            quantity: $(elementoQuantity).val(),
            quote: $(elementoQuote).val() == '' ? 'R$ 0,00' : $(elementoQuote).val(),
            provider_id: $('#id-provider').val(),
            product_id: $(elementoProductId).val(),
            product: '',
            link_item: $(elementoLink).val(),
            // product: {
            //     id: $(elementoProductId).val(),
            //     item_name: $(elementoItemName).val(),
            //     category_item_prod: $(elementoCategory).val(),
            //     manufacturer: $(elementoManufacturer).val(),
            //     model: $(elementoModel).val(),
            //     sku: $(elementoSku).val()
            // }
            //CÓDIGO COMENTADO PORQUE NÃO HÁ MAIS A NECESSIDADE DO PARCEIRO
            //EDITAR O PRODUTO BASE, APENAS OS CAMPOS DE ITENS
        }
        cotation_product.cotation_product_items.push(item);
    }
    return cotation_product;
}

function prepararCotationService(){
    let cotation_service = {
        id: '',
        description: $('#descricao-service').val(),
        category: $('#category-service').val(),
        collection_type: $('#tipo_cobranca').val(),
        cotation_id: $('#cotation_enviada').val(),
        orcamento_env: $("#exp-orcamento_serv").val(),
        exp_start_servico: $("#exp_start_servico").val(),
        tempo_estimado: $("#tempo_estimado_serv").val(),
        provider_id: $('#id-provider').val(),
    }
    return cotation_service;
}

function enviarAnexosCotationFonecedor(cotation_id, provider_id) {
    $("#cotation-reference-anexo").val(cotation_id);
    $("#provider-reference-anexo").val(provider_id);
    var formdata = new FormData($("#form-anexo-uteis-fornecedor")[0]);
    var link = "<?= $this->Url->build(['action' => 'saveAnexosFornecedor']) ?>";
    $.ajax({
        type: 'POST',
        url: link,
        data: formdata ,
        processData: false,
        contentType: false,
    }).done(function (data) {
       console.log('Anexou: ', data)
    });
}

var email_sent = false;
//AVISAR CLIENTE POR E-MAIL
function avisarClientePorEmail(client){
    let body = {};
    body['userId'] = client[0];
    body['namePartner'] = client[1];
    body['cotationIdClient'] = client[2];
    body['typeCotation'] = client[3];
    body['type'] = 2;
    let p = $.post("http://ngproc.com.br/sendgrid/index.php", body);
}


function salvarCotation(btn){
    //btn == 0 : Clicou em 'salvar e continuar'
    //btn == 1 : Clicou em 'salvar e concluir'
    if( !validarDadosCotacaoPorFornecedor() ){
        swal({
            title: "Por favor",
            text: "Para continuar, preencha todos os campos obrigatórios corretamente.",
            icon: "info",
            button: "Está bem"
        }).then(value => {
            validarDadosCotacaoPorFornecedor();
        });
    }else{
        let cotation = prepararCotation();
        let p = "";

        if($('#type-cotation').val() == 0){
            cotation.cotation_product = prepararCotationProduct();
            cotation.cotation_providers = [];
            cotation.cotation_providers.push({
                //id: "",
                cotation_id: "",
                provider_id: $('#id-provider').val(),
                deadline: $('#prazo-entrega').val() == "" ? 0 : $('#prazo-entrega').val(),
                cost: $('#cost_freight').val()  == "" ? 0 : $('#cost_freight').val(),
                user_id: '',
                //anexos: new FormData($('#responsecotation-form')[0]),
            });
            console.log('Cotation: ',cotation);
            p = $.post('<?= $this->Url->build(['action' => 'addCotationProduct']) ?>', cotation);
            carregarLoad(true);

        }else if($('#type-cotation').val() == 1){
            cotation.cotation_service = prepararCotationService();
            cotation.cotation_providers = {
                // id: "",
                cotation_id: "",
                provider_id: $('#id-provider').val(),
                deadline: '',
                cost: '',
                user_id: '',
            }
             p = $.post('<?= $this->Url->build(['action' => 'addCotationService']) ?>', cotation);
             carregarLoad(true);
        }

        p.done(function response(data) {
            carregarLoad(false);
            console.log(data.errors);
            if (data.result == 'success') {
                $('#cotation_enviada').val(data.idCotation);
                enviarAnexosCotationFonecedor(data.idCotation, $('#id-provider').val());
                if(email_sent == false && $('#type-cotation').val() == 0){
                    avisarClientePorEmail(data.data);
                    email_sent = true;
                }
                if(btn == 0){
                    if(contadorTelaFornecedor >= ($('#provider_qtd').val() - 1)){
                        swal({
                            title: "Cotação salva!",
                            text: `Porém, não é possível cadastrar mais fornecedores para esta cotação.
                            Vamos te redirecionar para a tela principal.`,
                            icon: "info",
                            button: "Finalizar"
                        }).then(value => {
                            window.location.href = '<?= $this->Url->build(['action' => 'index']) ?>';
                        });
                    }else{
                        // desabilitando botão para evitar erro de usuário clicar e salvar dado 2 vezes
                        $('#btn-salvar-continuar').prop("disabled","disabled");
                        $('#verde').prop("disabled","disabled");

                        swal("Sua cotação foi enviada.",{
                            icon: "success",
                            button: "Continuar"
                        }).then(value => {
                            $("#btn-abrir-modal-fornecedor").click();
                        });
                    }
                }else if(btn == 1){
                    swal("Sua cotação foi enviada.",{
                        icon: "success",
                        button: "Ok"
                    }).then(value => {
                        window.location.href = '<?= $this->Url->build(['action' => 'index']) ?>';
                    });
                }
            } else {
                //$(".error").html(data);
                swal("Falha ao enviar cotação.", {
                    icon: "error"
                }).then(value => {

                });
            }
        });
    }
}

$('#btn-salvar-continuar').click(function(){
    salvarCotation(0);
});

$('#verde').click(function(){
    salvarCotation(1);
});

// $('#preto').click(()=>{
//     swal("Porém, não é possível cadastrar mais fornecedores para esta cotação.",{
//         icon: "info",
//         button: "Está bem"
//     });
// });
</script>

<script>
$(document).ready(function() {
    $('#cnpj-provider').focusout(function() {
        let cnpj = $('#cnpj-provider').val();

        $.ajax({
            url: `<?= $this->Url->build(['action' => 'findProviderByCnpj']) ?>?cnpj=${cnpj}`,
            dataType: 'json',
            success: function(response) {
                if (response && response.data) {
                    let data = response.data;
                    //$('#id-provider').val(data.id);
                    $('#nome-provider').val(data.name);
                    $('#site-provider').val(data.site);
                    $('#tel-provider').val(data.telephone);
                    $('#cep-provider').val(data.address_zipcode);
                    $("#rua-provider").val(data.address_street);
                    $("#numero-provider").val(data.address_number);
                    $("#complemento-provider").val(data.address_complement);
                    $("#bairro-provider").val(data.address_neighborhood);
                    $("#cidade-provider").val(data.address_city);
                    $("#uf-provider").val(data.address_uf);

                    //DESABILITANDO OS CAMPOS PARA EDIÇÃO
                    // $('#nome-provider').prop("disabled","disabled");
                    // $('#site-provider').prop("disabled","disabled");
                    // $('#tel-provider').prop("disabled","disabled");
                    // $('#cep-provider').prop("disabled","disabled");
                    // $("#rua-provider").prop("disabled","disabled");
                    // $("#numero-provider").prop("disabled","disabled");
                    // $("#complemento-provider").prop("disabled","disabled");
                    // $("#bairro-provider").prop("disabled","disabled");
                    // $("#cidade-provider").prop("disabled","disabled");
                    // $("#uf-provider").prop("disabled","disabled");
                }
            }
        });
    });

    $("#cep-provider").focusout(function() {
        //Início do Comando AJAX
        $.ajax({
            //O campo URL diz o caminho de onde virá os dados
            //É importante concatenar o valor digitado no CEP
            url: 'https://viacep.com.br/ws/' + $(this).val() + '/json/unicode/',
            //Aqui você deve preencher o tipo de dados que será lido,
            //no caso, estamos lendo JSON.
            dataType: 'json',
            //SUCESS é referente a função que será executada caso
            //ele consiga ler a fonte de dados com sucesso.
            //O parâmetro dentro da função se refere ao nome da variável
            //que você vai dar para ler esse objeto.
            success: function(resposta) {
                //Agora basta definir os valores que você deseja preencher
                //automaticamente nos campos acima.
                $("#rua-provider").val(resposta.logradouro);
                //$("#complemento-provider").val(resposta.complemento);
                $("#bairro-provider").val(resposta.bairro);
                $("#cidade-provider").val(resposta.localidade);
                $("#uf-provider").val(resposta.uf);
                //Vamos incluir para que o Número seja focado automaticamente
                //melhorando a experiência do usuário
                $("#numero-provider").focus();
            }
        });
    });
});
</script>
<script>
$('.btn-calcular-orcamento').click(function() {
    let total = 0;
    let checkbox = '';
    for (let i = 0; i < $('#qtd_itens_cotation').val(); i++) {
        let classe = ".check" + i;
        if($(classe).is(':checked')){
            let elementoQuantity = "#quantity" + i,
                elementoQuote = "#quote" + i;
            let qtd = parseInt($(elementoQuantity).val());
            let valor = $(elementoQuote).val();
            var clean = parseFloat(valor.replace(/[^0-9,]*/g, '').replace(',', '.')).toFixed(2);

            total = total + (qtd * clean);
        }
    }

    let valorFormatado = total.toLocaleString('pt-BR', { style: 'currency', currency: 'BRL' });
    $('.mostrar-orcamento').val(valorFormatado);
    $('.mostrar-orcamento').focus();
});
</script>

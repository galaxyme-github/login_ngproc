<?php $this->assign('title', 'Minhas Cotações');?>

<?= $this->Html->css("font-awesome.min"); ?>
<div class="dashboard-container">
    <div class="ngproc-card">
        <div class="ngproc-card-title">
            Listagem de cotações
        </div>
        <div class="ngproc-card-content">
            <div class="row">
                <div id="containerx" class="col-xs-12">
                    <div class="find-row">
                        <div id="nova" class="box-header">
                           <?=$this->Html->link('Nova cotação', ['action' => 'add'], ['class' => 'cliente-cadastro btn btn-primary btn-sm']);?>
                        </div>
                        <form method="GET" class="form-horizontal box-header">
                            <div class="form-group" id="buscar">
                                <label for="" class="col-sm-2 control-label">Buscar:</label>
                                <div  class="col-sm-10 div-busca-parceiro">
                                    <div>
                                        <input type="text" class="input-sm" name="cotacao-search">
                                        <span id="clear-search" title="Limpar busca" onclick="window.location.href='<?=$this->Url->build(['action' => 'index'])?>'"><?=$this->Html->image('icons/delete-button.png')?></span>
                                    </div>
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
                                <th>Expectativa de orçamento</th>
                                <th>Data de inclusão</th>
                                <th>Prazo para conclusão</th>
                                <th>Status</th>
                                <th width="50px">Ação</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $cotationsVazia = true;
                            foreach ($cotations as $cotation): ?>
                                <?php !empty($cotation) ? $cotationsVazia = false : $cotationsVazia = true ?>
                                <tr <?= $cotation->status == 2 || $cotation->status == 3 ? 'style="opacity: .6;"' : '' ?>
                                    class="tr-link"
                                    onclick="<?php

                                                switch ($cotation->status) {
                                                    case 0:
                                                        echo "window.location.href='" .$this->Url->build(['action' => 'edit', $cotation->id])."'";
                                                        break;
                                                    case 1:
                                                        echo "window.location.href='" .$this->Url->build(['action' => 'view', $cotation->id])."'";
                                                    break;
                                                    case 2:
                                                        echo "window.location.href='" .$this->Url->build(['action' => 'edit', $cotation->id])."'";
                                                        break;
                                                    case 3:
                                                        echo "reativarCotation(".$cotation->id.")";
                                                        break;
                                                    case 4:
                                                        echo "";
                                                        break;
                                                    case 5:
                                                        echo "";
                                                        break;
                                                    default:
                                                    echo "window.location.href='" .$this->Url->build(['action' => 'view', $cotation->id])."'";
                                                }
                                            ?>
                                    "
                                >

                                    <td><?=$cotation->id?></td>
                                    <td><?=$cotation->title?></td>
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
                                    <!-- <td><?="R$" . $this->Number->format($cotation->getBudgetExpectation(), ['places' => 2, 'locale' => 'pt_BR'])?></td> -->
                                    <td><?php
                                            if($cotation->type == 0 && property_exists($cotation, 'cotation_product') && property_exists($cotation->cotation_product, 'estimate')) {
                                                echo "R$ " . $this->Number->format($cotation->cotation_product->estimate, ['places' => 2, 'locale' => 'pt_BR']);
                                            }else{
                                                echo "R$ " . $this->Number->format($cotation->getBudgetExpectation(), ['places' => 2, 'locale' => 'pt_BR']);
                                            }
                                        ?>
                                    </td>
                                    <td><?=$cotation->created->format('d/m/Y')?></td>
                                    <td><?=$cotation->deadline_date?></td>

                                    <td>
                                        <?php
                                            switch ($cotation->status) {
                                                case 0:
                                                    echo "Aguardando parceiro";
                                                    break;
                                                case 1:
                                                    echo "Em andamento";
                                                    break;
                                                case 2:
                                                    echo "Expirada";
                                                    break;
                                                case 3:
                                                    echo "<span style='color:#c0392b'>Cancelado</span>";
                                                    break;
                                                case 4:
                                                    echo "Rejeitado";
                                                    break;
                                                default:
                                                echo "<span style='color:#27ae60'>Concluído</span>";
                                            }
                                        ?>
                                    </td>

                                    <td class="arrow"><img src="/img/icons/arrow-point-to-right.png"></td>
                                </tr>
                            <?php endforeach;?>
                        </tbody>
                    </table>
                    <?php
                        //MOSTARNDO MENSAGEM CASO NÃO TENHA NENHUMA COTAÇÃO
                        if($cotationsVazia){
                            echo "<div style='text-align:center'>Você ainda não possui nenhuma cotação criada.</div>";
                        }
                    ?>
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

 <!-- Modal -->
 <div class="modal fade" id="evaluaion" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                <div class="modal-dialog" role="document" style="margin-top:30vh">
                    <div class="modal-content">

                        <div class="row">
                        <h5 class="col-md-12 px-4" id="exampleModalLabel" style="padding: 12px 15%; font-size:28px;margin-left: auto;margin-right: auto;
            text-align: center;">Avaliações pendentes !</h5>
                        </div>
                        <div id="evaluations">

                        </div>
                        <div class="row" style="display: flex;justify-content: center;padding: 20px 0px">
                            <button class="btn btn-primary" data-dismiss="modal">Avaliar mais tarde</button>
                        </div>
                        <!-- <div class="row" style="display:flex;padding: 30px 100px;justify-content: center;">
                            <div class="col" style="margin-left: 20px">
                                <button type="button" class="btn btn-primary" onclick="avaliarParceiro()" data-dismiss="modal" >Confirmar</button>
                            </div>
                        </div> -->

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
            text: `NGProc – Next Generation Procurement é uma plataforma que conecta profissionais da área de Supply chain, Compras e Procurement até você, seja uma empresa ou uma pessoa física, a um preço muito competitivo.  O resultado é redução de custo consistente.
            Possuímos dois tipos de rede a Rede NGP e a Rede Corporate;
            Rede NGP é voltada para empresas e consumidores.  Nossos parceiros pesquisam, de forma profissional, o melhor fornecedor para sua demanda.  Ao final poderá ou não efetuar a compra através da NGProc
            A Rede Corporate é voltada para projeto de empresas.  Nossa rede Corporate está preparada para receber demandas altamente qualificadas.  Os parceiros Corporate possuem comprovada experiencia nas áreas de Supply Chain, Compras e Procurement.  O parceiro Corporate desenvolve projetos nessas áreas.`,
            button: "Criar cotação",
        }).then(function() {
                window.location = "<?=$this->Url->build([ 'controller' => 'Quotation', 'action' => 'add'])?>";
            });
    }
    let contatorAvaliacoes = 0
    $.get('<?=$this->Url->build([ 'controller' => 'Evaluations', 'action' => 'index'])?>', function(data) {

        let evaluations = JSON.parse(data);
        if (evaluations.length == 0) return;
        contatorAvaliacoes = evaluations.length
        $('#evaluaion').modal('show');
        console.log(evaluations)
        let str = '';
        evaluations.forEach((element) => {
            var nome = element.parter.name.split(' ')
            var primeiroNome = nome[0][0] + "."
            var segundoNome = nome[1] || " "
            str += `    <div class="row" id="linha${element.id}" style="display: flex;justify-content: center;">
                            <div class="col-md-2 text-center">
                                Cotação ${element.cotation_id}
                            </div>
                            <div class="col text-center" style="margin-right:20px;">
                                <p> ${primeiroNome} ${segundoNome}</p>
                            </div>

                            <div class="col" style="margin-right:20px">
                                <div class="estrelas">
                                    <input type="radio" id="cm_star-empty" name="fb${element.id}" value="" checked/>
                                    <label for="cm_star-${element.id}1"><i class="fa"></i></label>
                                    <input type="radio" id="cm_star-${element.id}1" name="fb${element.id}" value="1"/>
                                    <label for="cm_star-${element.id}2"><i class="fa"></i></label>
                                    <input type="radio" id="cm_star-${element.id}2" name="fb${element.id}" value="2"/>
                                    <label for="cm_star-${element.id}3"><i class="fa"></i></label>
                                    <input type="radio" id="cm_star-${element.id}3" name="fb${element.id}" value="3"/>
                                    <label for="cm_star-${element.id}4"><i class="fa"></i></label>
                                    <input type="radio" id="cm_star-${element.id}4" name="fb${element.id}" value="4"/>
                                    <label for="cm_star-${element.id}5"><i class="fa"></i></label>
                                    <input type="radio" id="cm_star-${element.id}5" name="fb${element.id}" value="5"/>
                                </div>
                            </div>
                            <div class="col" style="margin-right:10px" onclick="avaliar('${element.id}')">
                                <i class="fa fa-check" aria-hidden="true"></i>
                            </div>
                            <div class="col" >
                                <i class="fa fa-times" aria-hidden="true" onclick="naoAvaliar('${element.id}')"></i>
                            </div>
                        </div>
                    `;
        });
        $('#evaluations').html(str)

    })
    function avaliar(idEvaluation) {
        let value = $(`input[name='fb${idEvaluation}']:checked`).val()
        let url = '<?=$this->Url->build([ 'controller' => 'Evaluations', 'action' => 'edit'])?>' + `/${idEvaluation}`;
        $.post( url, { value })
        .done(function( data ) {
            console.log(data);
        });
        removerLinha(idEvaluation);

    }
    function naoAvaliar(idEvaluation) {
        let value = -1
        let url = '<?=$this->Url->build([ 'controller' => 'Evaluations', 'action' => 'edit'])?>' + `/${idEvaluation}`;
        $.post( url, { value })
        .done(function( data ) {
            console.log(data);
        });
        removerLinha(idEvaluation)

    }

    function removerLinha(idEvaluation) {
        $(`#linha${idEvaluation}`).toggle();
        contatorAvaliacoes--;
        if (contatorAvaliacoes <= 0) {
            $('#evaluaion').modal('hide');
        }
    }

    function reativarCotation(id){
        body = {
            cotation_id: id,
        }
        console.log(body);
        swal({
            title: "Cotação cancelada",
            text: "Você deseja reativar sua cotação?.",
            icon: "info",
            buttons: ['Não','Sim, por favor'],
            dangerMode: true,
        })
        .then((willDelete) => {
            if (willDelete) {
                let p = $.post('<?= $this->Url->build(['action' => 'active-cotation']) ?>', body);
                carregarLoad(true);
                p.done(function response(data) {
                    carregarLoad(false);
                    if (data.result == 'success') {
                        console.log(data.data);
                        swal("Cotação reativada com sucesso.", {
                            icon: "success"
                        }).then(value => {
                            window.location = "<?= $this->Url->build(['action' => 'index']) ?>";
                        });
                    } else {
                        //$(".error").html(data);
                        swal("Falha ao reativar cotação.", {
                            icon: "error"
                        }).then(value => {

                        });
                    }
                });
            } else {

            }
        });
    }
    // function exibirAlerta() {
    //     swal({
    //         title: 'Nenhum envio encontrado',
    //         text: 'Pedimos que aguarde enquanto nossos parceiros preparam suas cotações.',
    //         icon: 'info',
    //         button: "Está bem"
    //     });
    // }
    // function exibirAlertaExpirada() {
    //     swal({
    //         title: 'Cotação Expirada',
    //         text: 'Entre em contado com o admin para atualizá-la caso deseje.',
    //         icon: 'info',
    //         button: "Está bem"
    //     });
    // }
</script>

<style>
.estrelas label{
    font-family: none!important;
}

.estrelas input[type=radio] {
  display: none;
}
.estrelas label i.fa:before {
  content:'\f005';
  color: #FC0;
  font-size: 15px;
}
.estrelas input[type=radio]:checked ~ label i.fa:before {
  color: #CCC;
}
</style>

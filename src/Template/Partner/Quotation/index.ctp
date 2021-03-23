<?php $this->assign('title', 'Cotações Recebidas');

/*
Menu
 */
?>

<?= $this->Html->css("font-awesome.min"); ?>

<div class="dashboard-container">
    <div class="ngproc-card">
        <div class="ngproc-card-title">
            Listagem de cotações
        </div>
        <!-- <a href="<?=$this->Url->build(['action' => 'insertCotations'])?>">Inserir Cotações</a> -->
        <div class="ngproc-card-content">
            <div class="row">
                <div id="containerx" class="col-xs-12">
                    <div class="find-row">
                        <div id="nova"></div>
                        <form method="GET" class="form-horizontal">
                            <div class="form-group">
                                <label for="" class="col-sm-2 control-label">Buscar:</label>
                                <div class="col-sm-10 div-busca-parceiro">
                                    <div>
                                        <input type="text" class="input-sm" name="cotacao-search">
                                        <span id="clear-search" title="Limpar busca" onclick="window.location.href='<?= $this->Url->build(['action' => 'index']) ?>'"><?= $this->Html->image('icons/delete-button.png') ?></span>
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
                                <th width="50px">Detalhar</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $mostrarMensagemSemCotation = true;
                            foreach ($cotations as $cotation) : ?>
                                <?php //if($cotation->type != 1):?>
                                <?php if($cotation->status == 0 || $cotation->status == 1) : ?>
                                    <?php $mostrarMensagemSemCotation = false ?>
                                    <tr class="tr-link" onclick="window.location.href='<?= $this->Url->build(['action' => 'view', $cotation->id]) ?>'">
                                        <td><?= $cotation->id ?></td>
                                        <td><?= $cotation->title ?></td>
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
                                        <!--Corrigir aqui para listar os productsItems -->
                                        <td><?php
                                            if($cotation->type == 0){
                                                $orcamento = 0;
                                                foreach ($cotation->cotation_product->cotation_product_items as $item) {
                                                    $orcamento = $orcamento + ($item->quote * $item->quantity);
                                                }
                                                echo "R$ " . $this->Number->format($orcamento, ['places' => 2, 'locale' => 'pt_BR']);
                                            }else{
                                                echo "R$ " . $this->Number->format($cotation->getBudgetExpectation(), ['places' => 2, 'locale' => 'pt_BR']);
                                            }
                                        ?>
                                    </td>
                                        <!--exibi o orçamento com virgula-->
                                        <td><?= $cotation->created->format('d/m/Y') ?></td>
                                        <td><?= $cotation->deadline_date ?></td>
                                        <td>
                                            <?php
                                                switch ($cotation->status) {
                                                    case 0:
                                                        echo "Aguardando parceiro";
                                                        break;
                                                    case 1:
                                                        // echo "Em andamento";
                                                        echo "Aguardando parceiro";
                                                        //Não é necessário que o parceiro veja o status em andamento
                                                        //Nessa tela, status 1 significa poder fazer um envio
                                                        //As cotações que ele já enviou nem estão contidas no array
                                                        break;
                                                    case 2:
                                                        echo "Expirada";
                                                        break;
                                                    case 3:
                                                        echo "Cancelado";
                                                        break;
                                                    case 4:
                                                        echo "Rejeitado";
                                                        break;
                                                    default:
                                                    echo "Concluído";
                                                }
                                            ?>
                                        </td>
                                        <td class="arrow"><img src="/img/icons/arrow-point-to-right.png"></td>
                                    </tr>
                                <?php endif; ?>
                                <?php //endif; ?>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                    <?php
                    //MOSTARNDO MENSAGEM CASO NÃO TENHA NENHUMA COTAÇÃO
                    if ($mostrarMensagemSemCotation) {
                        echo "<div style='text-align:center'>Ainda não possuímos nenhuma cotação de sua categoria.</div>";
                    }
                    ?>
                    <div id="" class="col-xs-12 col-md-12">
                        <ul class="pagination pagination-sm no-margin pull-right">
                            <?php
                            echo $this->Paginator->prev('<');
                            if (!empty($cotation)) {
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
    <script src="<?= $this->Url->build('bower_components/bootstrap/dist/js/bootstrap.min.js') ?>"></script>
    <!-- Sweetalert -->
    <script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
    <script>
        let firstLogin = <?= $firstLogin ? 'true' : 'false' ?>;
        if (firstLogin) {
            swal({
                title: "Bem vindo!",
                text: `Parceiro NGP,
Seja muito bem-vindo(a) a NGProc.  Estaremos trabalhando juntos para oferecermos aos nossos clientes os melhores preços e condições.  E lembre-se ao enviar uma cotação tenha certeza de que as informações estejam corretas e que os preços sejam competitivos.
Desejamos sucesso!
NGProc`,
                button: "Complete seus dados",
            }).then(function() {
                window.location = "<?= $this->Url->build(['controller' => 'Profile', 'action' => 'index']) ?>";
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
            str += `    <div class="row" id="linha${element.id}" style="display: flex;justify-content: center;">                            
                            <div class="col-md-4">
                                Cotação ${element.cotation_id}
                            </div>
                            <div class="col-md-4">
                                ${element.cotation.title}
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

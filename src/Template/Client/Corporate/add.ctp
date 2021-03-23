
<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Project $project
 */
?>


<?php $this->assign('title', 'Novo Projeto');?>
    <?=$this->Html->css('https://maxcdn.bootstrapcdn.com/bootstrap/3.4.0/css/bootstrap.min.css');?>
    <div class="dashboard-container">
        <div class="ngproc-card">
            <div class="ngproc-card-title">
                <?=$this->Html->link('< Corporate', ['action' => 'index'], ['class' => 'btn', 'id' => 'voltar']);?>
            </div>
            <div class="ngproc-card-content">
            <?= $this->Form->create() ?>
                <div id="box-novo-projeto">
                    <div class="row">
                        <div class="col-sm-6">
                            <div class="row"><label for="txt-descricao-projeto">Descrição do projeto *</label></div>
                            <div class="row"><textarea id="txt-descricao-projeto" rows="6" minlength="10" maxlength="255" placeholder="Descreva seu projeto em até 255 caracteres" name="description" required style="width: 90%"></textarea></div>
                        </div>
                        <div class="col-sm-6">
                            <div class="row">
                                <label>Como você deseja distribuir o seu projeto? *</label>
                                <br>
                                <div>
                                    <input type="radio" id="distribuir-1" name="distribution" value="1">
                                    <label for="distribuir-1" style="font-weight:500">Destribuir para todos</label>
                                    <br>
                                    <input type="radio" id="distribuir-2" name="distribution" value="2">
                                    <label for="distribuir-2" style="font-weight:500">Escolher um profissional da rede corporate</label>
                                    <br>
                                </div>
                            </div>
                            <div class="row" style="margin-top:18px">
                                <label for="tp-estimado-projeto">Tempo estimado do projeto em horas *</label>
                                <input id="tp-estimado-projeto" class="form-control" type="number" placeholder="2 horas" min="1" style="max-width: 320px" name="hours" required>
                            </div>
                        </div>
                    </div>

                    <div class="row" id="box-table-kips">
                        <label>Informe seus KPIs para este projeto:</label>
                        <table class="table table-bordered table-responsive">
                            <thead>
                                <tr>
                                    <th>Nome do indicador *</th>
                                    <th>Tipo de meta *</th>
                                    <th>Valor de meta *</th>
                                    <th style="text-align:center">
                                        <button type="button" class="btn btn-primary" onclick="addNovoKip()" style="width:25px;height:25px;background-color:#004268;"><img src="<?= $this->Url->build("img/icons/add.png") ?>" style="width:15px; margin: -10px 0 0 -7px"></button>
                                    </th>
                                </tr>
                            </thead>
                            <tbody id="tbody-kpis">
                                <tr>
                                    <td>
                                        <input class="form-control indicador" type="text" placeholder="Indicador" name="name[]" required>
                                    </td>
                                    <td>
                                        <select class="form-control tipo-meta" name="meta_type[]" id="" required>
                                            <option value="" selected disabled>Selecione</option>
                                            <option value="1">Percentual</option>
                                            <option value="2">Tipo 2</option>
                                            <option value="3">Tipo 3</option>
                                        </select>
                                    </td>
                                    <td>
                                        <input class="form-control dinheiro-real valor-meta" type="text" placeholder="R$ 0,00" name="meta_value[]" required>
                                    </td>
                                    <td style="text-align:center">
                                        <img class="btn-cancel" src="<?=$this->Url->build('img/icons/cancel.png')?>" width="24" height="24" alt="excluir" onclick="removerItem()" style="cursor:pointer; margin-top:5px">
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
                <div id="juntos">
                    <input type="submit" class="btn btn-primary" id="verde" value="Prosseguir" style="background-color:#004268;">
                    <?=$this->Html->link(' Voltar', ['action' => 'index'], ['class' => 'btn btn-dark', 'id' => 'preto']);?>
                </div>
                <?=$this->Form->end();?>
                <!-- fim card content -->
            </div>
        </div>
    </div>

<!-- jQuery 3 -->
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
<!-- <script src="<?=$this->Url->build('bower_components/jquery/dist/jquery.min.js')?>"></script> -->
<!--Ajax-->
<script src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.js"></script>
<!-- <script src="<?=$this->Url->build('bower_components/jquery-ui/jquery-ui.min.js')?>"></script> -->
<!-- jQuery Mask Plugin -->
<script src="<?=$this->Url->build('bower_components/jQuery-Mask-Plugin-master/dist/jquery.mask.min.js')?>"></script>
<!-- Sweetalert -->
<script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
<!-- Bootstrap -->
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.0/js/bootstrap.min.js"></script>
<!-- jquery-maskmoney -->
<script src="<?=$this->Url->build('bower_components/plentz-jquery-maskmoney-cdbeeac/dist/jquery.maskMoney.js')?>"></script>

<script>
  
    //mascara para R$
    $(".dinheiro-real").maskMoney({prefix:'R$ ', allowNegative: true, thousands:'.', decimal:',', affixesStay: false});
</script>

<script>
    //ADICIONANDO NOVA LINHA NA TABELA DE KPIS
    function addNovoKip(){
        $('#tbody-kpis').append(`
            <tr>
                <td>
                    <input class="form-control indicador" type="text" placeholder="Indicador" name="name[]" required>
                </td>
                <td>
                    <select class="form-control tipo-meta" name="meta_type[]" id="" required>
                        <option value="" selected disabled>Selecione</option>
                        <option value="1">Percentual</option>
                        <option value="2">Tipo 2</option>
                        <option value="3">Tipo 3</option>
                    </select>
                </td>
                <td>
                    <input class="form-control dinheiro-real valor-meta" name="meta_value[]" type="text" placeholder="R$ 0,00" required>
                </td>
                <td style="text-align:center">
                    <img class="btn-cancel" src="<?=$this->Url->build('img/icons/cancel.png')?>" width="24" height="24" alt="excluir" onclick="removerItem()" style="cursor:pointer; margin-top:5px">
                </td>
            </tr>
        `);
        //mascara para R$
        $(".dinheiro-real").maskMoney({prefix:'R$ ', allowNegative: true, thousands:'.', decimal:',', affixesStay: false});
    }

    //remove linha da tabela de cotação de produtos ao clicar na imagem de delete
    $("#tbody-kpis").on('click','.btn-cancel',function(e) {
        if($('#tbody-kpis tr').length > 1){
            $(this).closest("tr").remove();
        }else{
            swal({
                title: "Sinto muito",
                text: "O projeto precisa de ao menos um item KPI.",
                icon: "error",
                button: "Está bem"
            });
        }
    });
</script>
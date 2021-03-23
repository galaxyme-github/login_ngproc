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
                            <button class="btn btn-primary" id="btn-abrir-modal" data-toggle="modal" data-target="#modal-corporate-partner">Novo convite</button>
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
                <!-- <div id="" class="col-xs-12 col-md-12">
                    <ul class="pagination pagination-sm no-margin pull-right">
                        <?php
                            echo $this->Paginator->prev('<');
                            echo $this->Paginator->numbers(['modulus' => 4]);
                            echo $this->Paginator->next('>');
                        ?>
                    </ul>
                </div> -->
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="modal-corporate-partner" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <!-- TELA 01 -->
            <div id="tela-01">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close" id="btn-close-modal"><span aria-hidden="true">&times;</span></button>
                    <h3 class="modal-title">NG PROC CORPORATE <img src="<?=$this->Url->build('img/icons/corporate-a.png')?>" alt="Corporate" width="27" height="19" style="margin:-6px 0 0 5px"></h3>
                </div>
                <div class="modal-body">
                    <p>Possui experiência em projetos e gestão na área de Supply Chain, Compras ou Procurement? Então cadastre-se em nosso sistema e iremos analizar.</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-primary" id="btn-prosseguir-etp-1">Solicitar convite</button>
                    <button type="button" class="btn btn-default" data-dismiss="modal">Voltar</button>
                </div>
            </div>
            <!-- FIM TELA 01 -->
            <!-- TELA 02 -->
            <div id="tela-02">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close" id="btn-close-modal"><span aria-hidden="true">&times;</span></button>
                    <h3 class="modal-title">Preencha os dados abaixo</h3>
                </div>
                <?= $this->Form->create(null, ['id' => 'form-qualificacao-parceiro','type' => 'file', 'enctype' => 'multipart/form-data']) ?>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-sm-12">
                            <div class="row"><label for="txt-qualificacoes-parceiro">Resumo de qualificações *</label></div>
                            <div class="row"><textarea name="text-qualificacoes" id="txt-qualificacoes-parceiro" cols="52" rows="8" minlength="10" maxlength="255" placeholder="Descreva suas qualificações em até 255 caracteres" required></textarea></div>
                        </div>
                    </div>
                    <div class="row" id="box-anexo">
                        <label for="anexo-parceiro">Envie seu curriculum</label>
                        <div class="col-sm-12 box-anexo-new-quotation btn btn-sm">
                            <input type="file" name="anexo[]" id="anexo-parceiro">
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary" id="btn-prosseguir-etp-2">Enviar</button>
                    <button type="button" class="btn btn-default" data-dismiss="modal">Fechar</button>
                </div>
                <?= $this->Form->end(); ?>
            </div>
            <!-- FIM TELA 02 -->
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->

<!-- Bootstrap 3.3.7 -->
<script src="<?=$this->Url->build('bower_components/bootstrap/dist/js/bootstrap.min.js')?>"></script>
<!-- jQuery 3 -->
<script src="<?= $this->Url->build('bower_components/jquery/dist/jquery.min.js') ?>"></script>
<!-- Sweetalert -->
<script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
<script>
    let firstLogin = <?=$firstLogin ? 'true' : 'false'?>;
    if (firstLogin) {
        $('#tela-02').hide();
        $('#tela-01').show();
        $('#btn-abrir-modal').click();
    }

    $('#btn-abrir-modal').click(function(){
        $('#tela-02').hide();
        $('#tela-01').show();
    });

    $('#btn-prosseguir-etp-1').click(function() {
        $('#tela-01').hide();
        $('#tela-02').show();
    });

    // $('#form-qualificacao-parceiro').submit(function(event) {
    //     event.preventDefault();

    //     //var form = document.getElementById('form-qualificacao-parceiro');
    //     var formData = new FormData(this);
    //     //array = [1,2];

    //     let p = $.post('<?= $this->Url->build(['action' => 'add']) ?>', formData);

    //     p.done(function response(data) {
    //             if (data.result == 'success') {
    //                 $('.close').click();
    //                 swal({
    //                     title: 'Obrigado',
    //                     text: 'Você receberá uma notificação após a análise do seu perfil.',
    //                     icon: 'success',
    //                     button: "Está bem"
    //                 });
    //             } else {
    //                 swal("Falha ao enviar cotação", {
    //                     icon: "error"
    //                 }).then(value => {
    //                     //window.location.href = '<?= $this->Url->build(['action' => 'index']) ?>';
    //                 });
    //             }
    //         })

    //     // $.ajax({
    //     //     url: <?= $this->Url->build(['action' => 'add']) ?>,
    //     //     type: "POST",
    //     //     data: formData,
    //     //     processData: false,
    //     //     contentType: false,
    //     //     success: function(data){
    //     //             $('.close').click();
    //     //             swal({
    //     //                 title: 'Obrigado',
    //     //                 text: 'Você receberá uma notificação após a análise do seu perfil.',
    //     //                 icon: 'success',
    //     //                 button: "Está bem"
    //     //             });
    //     //     }
    //     // });
        
    // });
</script>
<script>
//Resgatando resultado do form enviado
// if(<?=!empty($response)?> == true){
        if(<?=$response?> == 'success'){
            swal({
                title: 'Obrigado',
                text: 'Você receberá uma notificação após a análise do seu perfil.',
                icon: 'success',
                button: "Está bem"
            });
        }else{
            swal({
                title: 'Ops',
                text: 'Não foi possível salvar seus dados',
                icon: 'error',
                button: "Está bem"
            });
        }
    // }
</script>


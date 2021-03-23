
<?php $this->assign('title', 'Clientes');?>

<div class="dashboard-container">
    <div class="ngproc-card">
        <div class="ngproc-card-title">
        <!-- Clientes Cadastrados: <?= $this->Number->format(count($clients), ['places' => 0, 'locale' => 'pt_BR']);?> -->
        </div>
        <div class="ngproc-card-content">
            <div class="row">
                <div class="col-xs-12">
                    <div class="find-row">
                        <!-- Removido por enquanto do layout até segunda ordem -->
                        <!-- <div>
                            <button class="cliente-cadastro btn btn-primary btn-sm">Novo Usuário</button>
                        </div> -->
                        <form  method="get" class="form-horizontal">
                            <div class="form-group">
                                <label for="" class="col-sm-2 control-label">Buscar:</label>
                                <div class="col-sm-10 div-busca-parceiro">
                                    <div>
                                        <input type="text" class="input-sm" name="cliente-search">
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
                                <th>Nome</th>
                                <th>CPF</th>
                                <th>Email</th>
                                <th>Data Cadastro</th>
                                <th colspan="3" width="100px">Ação</th>
                            </tr>
                        </thead>
                        <tbody>
                        <?php foreach ($clients as $client): ?>
                            <tr>
                                <td><?=$client->id?></td>
                                <td><?=$client->name?></td>
                                <td><?=$client->doc_cpf?></td>
                                <td><?=$client->email?></td>
                                <td><?=$client->created->format('d/m/Y')?></td>
                                
                                <?php if($client->active == 0): ?>
                                
                                <td class="tr-link arrow" onclick="ativarUsuario('<?=$client->id?>','<?=$client->name?>')">
                                    <img src="/img/icons/sinais-d.png" width="16">
                                </td>
                                
                                <?php else: ?>

                                <td class="tr-link arrow" onclick="suspenderUsuario('<?=$client->id?>','<?=$client->name?>')">
                                    <img src="/img/icons/sinais.png" width="16">
                                </td>

                                <?php endif; ?>
                                
                                <td class="tr-link arrow" onclick="deletarUsuario('<?=$client->id?>','<?=$client->name?>')">
                                    <img src="/img/icons/delete-button.png" width="16">
                                </td>
                                <td class="tr-link arrow" onclick="window.location.href='<?=$this->Url->build(['action' => 'edit', $client->id])?>'">
                                    <img src="/img/icons/arrow-point-to-right.png">
                                </td>
                            </tr>
                        <?php endforeach;?>
                        </tbody>
                    </table>
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
<script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
<script>
    function deletarUsuario(id, name){
        swal({
            title: "Confirme",
            text: `Tem certeza que deseja excluir ${name} como cliente?
            
            Obs: Todas as cotações, ou qualquer outra ação do cliente que foram registradas em nossa base de dados serão perdidas.`,
            icon: "warning",
            buttons: ["Não","Sim"]
        })
        .then(value => {
            if(value) {
                let p = '', body = {};
                body['id'] = id;
                p = $.post('<?= $this->Url->build(['action' => 'delete']) ?>', body);
                carregarLoad(true);
                p.done(function response(data) {
                    if (data.result == 'success') {
                        carregarLoad(false);
                        console.log('ID: ', data.data)
                        swal({
                            title: "Feito!",
                            text: "Usuário excluído com sucesso.",
                            icon: "success",
                            button: "Ok"
                        }).then(value => {
                            window.location.href = '<?= $this->Url->build(['action' => 'index']) ?>';
                        });
                    } else {
                        carregarLoad(false);
                        swal({
                            title: "Falhou",
                            text:data.result,
                            icon: "error"
                        }).then(value => {
                            
                        });
                    }
                });
            }
        });
    }

    function suspenderUsuario(id, name){
        swal({
            title: "Confirme",
            text: `Você está tentando suspender ${name}. Deseja prosseguir?`,
            icon: "warning",
            buttons: ["Não","Sim"]
        })
        .then(value => {
            if(value) {
                let p = '', body = {};
                body['id'] = id;
                p = $.post('<?= $this->Url->build(['action' => 'desable']) ?>', body);
                carregarLoad(true);
                p.done(function response(data) {
                    if (data.result == 'success') {
                        carregarLoad(false);
                        console.log('ID: ', data.data)
                        swal({
                            title: "Feito!",
                            text: "Usuário suspendido com sucesso.",
                            icon: "success",
                            button: "Ok"
                        }).then(value => {
                            window.location.href = '<?= $this->Url->build(['action' => 'index']) ?>';
                        });
                    } else {
                        carregarLoad(false);
                        swal("Falha ao tentar suspender usuário.", {
                            icon: "error"
                        }).then(value => {
                            
                        });
                    }
                });
            }
        });
    }

    function ativarUsuario(id, name){
        swal({
            title: "Confirme",
            text: `Deseja permitir que ${name} volte às suas atividades como cliente?`,
            icon: "warning",
            buttons: ["Não","Sim"]
        })
        .then(value => {
            if(value) {
                let p = '', body = {};
                body['id'] = id;
                p = $.post('<?= $this->Url->build(['action' => 'enable']) ?>', body);
                carregarLoad(true);
                p.done(function response(data) {
                    if (data.result == 'success') {
                        carregarLoad(false);
                        console.log('ID: ', data.data)
                        swal({
                            title: "Feito!",
                            text: "Usuário reativado com sucesso.",
                            icon: "success",
                            button: "Ok"
                        }).then(value => {
                            window.location.href = '<?= $this->Url->build(['action' => 'index']) ?>';
                        });
                    } else {
                        carregarLoad(false);
                        swal("Falha ao tentar ativar usuário.", {
                            icon: "error"
                        }).then(value => {
                            
                        });
                    }
                });
            }
        });
    }
</script>

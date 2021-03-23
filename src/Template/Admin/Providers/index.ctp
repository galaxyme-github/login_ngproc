
<?php $this->assign('title', 'Fornecedores');

function encurtarLink($link){
    /**
     * TRATA APENAS ESSES CASOS
     * http://www.ngproc.com.br      // ngproc
     * https://www.ngproc.com.br";   // ngproc
     * ngproc.com.br;                // ngproc
     * www.ngproc.com.br;            // ngproc
     */
    $link = strtolower($link);
    $start = 0;
    $new_link = substr($link,$start,7);
    if($new_link === 'http://'){
    	$start = 7;
        $new_link = substr($link,$start);
    }else{
        $new_link = substr($link,0,8);
    	if($new_link === 'https://'){
    		$start = 8;
    		$new_link = substr($link,$start);
    	}
    }

    if($start == 7 || $start == 8){
    	$arr = str_split($new_link);

	    if($arr[0] == 'w' && $arr[1] == 'w' && $arr[2] == 'w' ){
	    	$start = 4;
	    	$new_link = substr($new_link,$start);
	    	$arr = str_split($new_link);
	    }

	    $new_link = '';
	    foreach ($arr as $k => $value) {
	        if($value != '.'){
	            $new_link = $new_link . $value;
	        }else{
	            break;
	        }
	    }

    }else{

    	$arr = str_split($link);

    	if($arr[0] == 'w' && $arr[1] == 'w' && $arr[2] == 'w' ){
	    	$start = 4;
	    }

	    $new_link = substr($link,$start);
    	$arr = str_split($new_link);

	    $new_link = '';
	    foreach ($arr as $k => $value) {
	        if($value != '.'){
	            $new_link = $new_link . $value;
	        }else{
	            break;
	        }
	    }
    }

    return $new_link;

}
?>
<style>
    .content{
        width: 1000px;
    }
</style>
<div class="dashboard-container">
    <div class="ngproc-card">
        <div class="ngproc-card-title">
        <!-- Fornecedores Cadastrados: <?= $this->Number->format(count($providers), ['places' => 0, 'locale' => 'pt_BR']);?> -->
        </div>
        <div class="ngproc-card-content">
            <div class="row">
                <div class="col-xs-12">
                    <div class="find-row elements-providers">
                        <div>
                            <button class="cliente-cadastro btn btn-primary btn-sm"  onclick="window.location.href='<?=$this->Url->build(['action' => 'add'])?>'">Novo Fornecedor</button>
                        </div>
                        <form method="get" class="form-horizontal">
                            <div class="form-group">
                                <label for="" class="col-sm-2 control-label">Buscar:</label>
                                <div class="col-sm-10 div-busca-parceiro">
                                    <div>
                                        <input type="search" class="input-sm" name="parceiro-search">
                                        <span id="clear-search" title="Limpar busca" onclick="window.location.href='<?=$this->Url->build(['action' => 'index'])?>'"><?=$this->Html->image('icons/delete-button.png')?></span>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
                <div class="col-xs-12 col-md-12">
                    <table class="col-xs-12 col-md-12 clientes-tabela table table-bordered table-hover">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Razão social</th>
                                <th>CNPJ</th>
                                <th>Site</th>
                                <th>Telefone</th>
                                <th>Data Cadastro</th>
                                <th colspan="3" width="100px">Ação</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($providers as $provider): ?>
                                <tr class="tr-link">
                                    <td><?=$provider->id?></td>
                                    <td><?=$provider->name?></td>
                                    <td><?=$provider->cnpj?></td>
                                    <td><a href="<?='http://' . $provider->site?>" target="_blank"><?= encurtarLink($provider->site) ?></a></td>
                                    <td><?=$provider->telephone?></td>
                                    <td><?=$provider->created->format('d/m/Y')?></td>
                                    <?php if($provider->active == 0): ?>

                                    <td class="tr-link arrow" onclick="ativarFornecedor('<?=$provider->id?>','<?=$provider->name?>')">
                                        <img src="/img/icons/sinais-d.png" width="16">
                                    </td>

                                    <?php else: ?>

                                    <td class="tr-link arrow" onclick="suspenderFornecedor('<?=$provider->id?>','<?=$provider->name?>')">
                                        <img src="/img/icons/sinais.png" width="16">
                                    </td>

                                    <?php endif; ?>

                                    <td class="tr-link arrow" onclick="deletarFornecedor('<?=$provider->id?>','<?=$provider->name?>')">
                                        <img src="/img/icons/delete-button.png" width="16">
                                    </td>
                                    <td
                                        class="arrow"
                                        onclick="window.location.href='<?=$this->Url->build(['action' => 'edit', $provider->id])?>'"><img src="/img/icons/arrow-point-to-right.png"
                                    ></td>
                                </tr>
                            <?php endforeach;?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
<script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
<script>

function deletarFornecedor(id, name){
        swal({
            title: "Confirme",
            text: `Tem certeza que deseja excluir ${name} como Fornecedor?`,
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
                            text: "Fornecedor excluído com sucesso.",
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

function suspenderFornecedor(id, name){
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
                            text: "Fornecedor suspendido com sucesso.",
                            icon: "success",
                            button: "Ok"
                        }).then(value => {
                            window.location.href = '<?= $this->Url->build(['action' => 'index']) ?>';
                        });
                    } else {
                        carregarLoad(false);
                        swal("Falha ao tentar suspender fornecedor.", {
                            icon: "error"
                        }).then(value => {

                        });
                    }
                });
            }
        });
    }

    function ativarFornecedor(id, name){
        swal({
            title: "Confirme",
            text: `Deseja permitir que parceiros utilizem ${name} como fornecedor?`,
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
                            text: "Fornecedor reativado com sucesso.",
                            icon: "success",
                            button: "Ok"
                        }).then(value => {
                            window.location.href = '<?= $this->Url->build(['action' => 'index']) ?>';
                        });
                    } else {
                        carregarLoad(false);
                        swal("Falha ao tentar reativar fornecedor.", {
                            icon: "error"
                        }).then(value => {

                        });
                    }
                });
            }
        });
    }

</script>

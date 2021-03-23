<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Provider $provider
 */
?>
<?php $this->assign('title', 'Novo fornecedor');?>

<div class="dashboard-container">
    <div class="ngproc-card">
        <div class="ngproc-card-title">
            <?= $this->Html->link("< Listar fornecedores", ['action' => 'index'], ['class' => 'btn', 'id' => 'voltar']); ?>
        </div>
    </div>
</div>

    <?= $this->Form->create($provider) ?>
    <div class="row partner-form" id="partner-form">
    <br />
    <fieldset>        
           
            <div class="form-row">               
                <div class="col-md-6 form-group">
                    <?=$this->Form->control('name', array( 'label' => 'Nome','class' => 'form-control')); ?>
                </div>               
            <div class="col-md-6 form-group">
                <?=$this->Form->control('cnpj', array( 'class' => 'form-control')); ?>
            </div>               
            <div class="form-row">
                <div class="col-md-6 form-group">
                    <?= $this->Form->control('site', array('class' => 'form-control'));  ?>
                </div>
                <div class="col-md-6 form-group">
                    <?=$this->Form->control('telephone' , array( 'label' => 'Telefone', 'class' => 'form-control')); ?>
                </div>
            </div>
            <div class="form-row">
                <div class="col-md-6 form-group">
                    <?=$this->Form->control('address_zipcode', array( 'label' => 'CEP', 'class' => 'form-control')); ?>
                </div>
                <div class="col-md-6 form-group">
                    <?=$this->Form->control('address_number', array( 'label' => 'N°', 'class' => 'form-control')); ?>
                </div>
            </div>
            <div class="form-row">
                <div class="col-md-6 form-group">
                    <?=$this->Form->control('address_street', array( 'label' => 'Endereço', 'class' => 'form-control')); ?>
                </div>
                <div class="col-md-6 form-group">
                    <?=$this->Form->control('address_neighborhood', array( 'label' => 'Bairro', 'class' => 'form-control')); ?>            
                </div>            
            </div>
            <div class="form-row">
                <div class="col-md-6 form-group">
                    <?=$this->Form->control('address_city', array( 'label' => 'Cidade', 'class' => 'form-control')); ?>
                </div>
                <div class="col-md-6 form-group">
                    <?=$this->Form->control('address_uf', array( 'label' => 'UF', 'class' => 'form-control')); ?>
                </div>            
            </div>
            <div class="form-row">
                <div class="col-md-6 form-group">
                    <?=$this->Form->control('address_complement', array( 'label' => 'Complemento', 'class' => 'form-control ')); ?>    
                </div>
            </div>
           
        </fieldset>
        <br />
        <div class="form-row">
            <?= $this->Form->button(__('Salvar'), array('class' => 'btn btn-success col-md-2 btn-edit2')) ?>
            <a style="
                            padding-left: 55px;
                            padding-right: 55px;
                            background-color: black;
                            color:white;
                            border-radius: 4px;
                        " href="<?= $this->Url->build(['action' => 'index']) ?>" class="btn btn-dark col-md-2 btn-edit2">Voltar</a>
        </div>
    <?= $this->Form->end() ?>
</div>

<!-- jQuery 3 -->
<script src="<?= $this->Url->build('bower_components/jquery/dist/jquery.min.js') ?>"></script>
<!-- jQuery Mask Plugin -->
<script src="<?= $this->Url->build('bower_components/jQuery-Mask-Plugin-master/dist/jquery.mask.min.js') ?>"></script>
<!-- Sweetalert -->
<script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
<!-- jquery-maskmoney -->
<script src="<?= $this->Url->build('bower_components/plentz-jquery-maskmoney-cdbeeac/dist/jquery.maskMoney.js') ?>"></script>


<script>

$(document).ready(function($){        
        $('#address-zipcode').mask('00000-000');
        $('#telephone').mask('(00) 0000-0000');
        $('#cnpj').mask('00.000.000/0000-00');


        $('#cnpj').focusout(function() {
            let cnpj = $(this).val();
            
            $.ajax({
                url: `<?= $this->Url->build(['action' => 'findProviderByCnpj']) ?>?cnpj=${cnpj}`,
                dataType: 'json',
                success: function(response) {
                    if (response && response.data) {
                        alertarModal('Fornecedor já cadastrado!','danger',2300);
                    }
                }
            });
        });

        $("#address-zipcode").focusout(function() {            
            $("#address-number").val('');
            $("#address-street").val('...');
            $("#address-neighborhood").val('...');
            $("#address-city").val('...');
            $("#address-uf").val('...');
            
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
                    $("#address-street").val(resposta.logradouro);
                    //$("#complemento-provider").val(resposta.complemento);
                    $("#address-neighborhood").val(resposta.bairro);
                    $("#address-city").val(resposta.localidade);
                    $("#address-uf").val(resposta.uf);
                    //Vamos incluir para que o Número seja focado automaticamente
                    //melhorando a experiência do usuário
                    $("#address-number").focus();
                }
            });
        });
});
$( "#cnpj" ).blur(function() {
    if(!validarCNPJ(this.value)){
        alertarModal('CNPJ Informado é inválido','danger',2300);
        //alert(this.value);
        this.value='';
        
    }
});
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
//VALIDAÇÃO DO CNPJ NA MODAL DE ADD FORNECEDOR
function alertarModal(mensagem, cor, tempo) {
    $('#alert').fadeIn();

    alerta = `<div class="alert alert-` + cor + `" role="alert">
                        ` + mensagem + `
                </div>`;
    $('#alert').html(alerta);
    $('#alert').fadeOut(tempo);
}
</script>
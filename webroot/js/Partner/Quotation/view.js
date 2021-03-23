// Abrir tela inicial de participação da cotação
$("#tela3").hide();
$("#tela2").hide();
$("#tela1").show();

//Transitação entre as telas da cotação
// De "Participar" para "Adicionar Fornecedor"
$("#btn-participar").click(function() {
    $("#tela1").hide();
    $("#tela2").show();
    $("#btn-abrir-modal-fornecedor").click();
});

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
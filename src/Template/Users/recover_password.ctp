<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title>NGProc</title>
  <!-- Tell the browser to be responsive to screen width -->
  <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
  <!-- Bootstrap 3.3.7 -->
  <link rel="stylesheet" href="<?=$this->Url->build('bower_components/bootstrap/dist/css/bootstrap.min.css')?>">
  <!-- Font Awesome -->
  <link rel="stylesheet" href="<?=$this->Url->build('bower_components/font-awesome/css/font-awesome.min.css')?>">
  <!-- Ionicons -->
  <link rel="stylesheet" href="<?=$this->Url->build('bower_components/Ionicons/css/ionicons.min.css')?>">
  <!-- Theme style -->
  <link rel="stylesheet" href="<?=$this->Url->build('dist/css/AdminLTE.min.css')?>">

  <!-- css loader -->
  <link rel="stylesheet" href="<?=$this->Url->build('css/css-loader/css-loader.css')?>">
  <!-- iCheck -->
  <link rel="stylesheet" href="<?=$this->Url->build('plugins/iCheck/square/blue.css')?>">

  <?=$this->Html->css('login_custom');?>

  <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
  <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
  <!--[if lt IE 9]>
  <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
  <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
  <![endif]-->

  <!-- Google Font -->
  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,600,700,300italic,400italic,600italic">
</head>

<body class="hold-transition login-page">
<div class="login-container">
  <div class="row">
    <div class="col-md-12 col-sm-12 col-xs-12" style="padding: 0;">
      <?=$this->Flash->render()?>
    </div>
    <div class="col-md-6 col-sm-6 col-xs-6">
      <div class="login-banner" style="background-image: url('<?=$this->Url->build('img/Imagem-logo-login.png')?>');"></div>
    </div>
    <div class="col-md-6 col-sm-6 col-xs-6">
      <!-- /.login-logo -->
      <div class="login-box-body">
        <h1>Insira o e-mail cadastrado</h1>

        <?=$this->Form->create(null, ['id' => 'recover-form'])?>
          <div class="form-group has-feedback">
            <input type="email" name="email" id="form-email" class="form-control" placeholder="Email">
          </div>
          <div class="row">
                <button type="submit" class="btn btn-primary btn-block btn-flat btn-login-custom">Enviar senha</button>
          </div>

        <?=$this->Form->end();?>
      </div>
    </div>
  </div>

  <div class="login-box">

    <!-- /.login-box-body -->
  </div>
</div>
<!-- /.login-box -->

<!-- jQuery 3 -->
<script src="<?=$this->Url->build('bower_components/jquery/dist/jquery.min.js')?>"></script>
<!-- Bootstrap 3.3.7 -->
<script src="<?=$this->Url->build('bower_components/bootstrap/dist/js/bootstrap.min.js')?>"></script>

<!-- Sweetalert -->
<script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>

<!-- Plugins para show/hide password, por enquanto sem sucesso -->
<!-- <script src="<?=$this->Url->build('bower_components/Show-Hide-Password-Field-Text-with-jQuery-Bootstrap/dist/bootstrap-show-password.min.js')?>"></script> -->
<!-- <script src="https://unpkg.com/bootstrap-show-password@1.2.1/dist/bootstrap-show-password.min.js"></script> -->

<!-- iCheck -->
<script src="<?=$this->Url->build('plugins/iCheck/icheck.min.js')?>"></script>



<script>
    // checa se o e-mail é valido
    $('#form-email').blur(function () {
      let str = $(this).val();
      var re = /^(([^<>()[\]\\.,;:\s@\"]+(\.[^<>()[\]\\.,;:\s@\"]+)*)|(\".+\"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
      if(!re.test(str)){
        swal("Por favor informe um e-mail válido");
        this.value = '';
      }
    })

  $('#recover-form').submit(function (event) {
    event.preventDefault();
    $('.loader').fadeIn();
    let url = $(this).attr("action");

    let post = $.post(url, $('#recover-form').serialize());

    post.done(function (data) {
      $('.loader').fadeOut();
      let message = 'Ocorreu uma falha durante sua solicitação, tente novamente.';
      if (data.result == 'success') {
        message = 'Foi enviado para o seu e-mail uma nova senha de acesso (verifique também a caixa de SPAM).';
      } else if (data.result == 'notfound') {
        message = 'Não foi encontrado este e-mail em nossa base de dados.';
      }

      swal("Recuperação de senha", message)
      .then((value) => {
        window.location.href = "/users/login";
      });
    });
  });
</script>

<script>
  $(function () {

	  // $("#password").password('toggle');
    $('input').iCheck({
      checkboxClass: 'icheckbox_square-blue',
      radioClass: 'iradio_square-blue',
      increaseArea: '20%' /* optional */
    });
  });
</script>

</body>
</html>

<!DOCTYPE html>
<html>
<head>
  <link rel="shortcut icon" href="/img/icons/logo.png"/>
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

  <!-- iCheck -->
  <link rel="stylesheet" href="<?=$this->Url->build('plugins/iCheck/square/blue-login.css')?>">

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
    <div class="col-md-12" style="padding: 0;">
      <?=$this->Flash->render()?>
    </div>
    <div class="col-md-6 mobile">
      <div class="login-banner img-fluid" style="background-image: url('<?=$this->Url->build('img/Imagem-logo-login.png')?>');"></div>
    </div>
    <div class="col-md-6">
      <!-- /.login-logo -->
      <div class="login-box-body">
        <h1>Bem-vindo!</h1>

        <?=$this->Form->create()?>
          <div class="form-group has-feedback">
            <input type="email" name="email" class="form-control" placeholder="Email">

          </div>
          <div class="input-group">
            <input type="password" id="password" name="password" class="form-control password" placeholder="Senha" data-toggle="password">
            <div onclick="Senha()" class="input-group-addon">
              <img src="/img/icons/eye_password.png" width="20" height="20">
            </div>
         </div>
<?php

// script de senha

?>


<script>
    function Senha(){
      var tipo = document.getElementById("password");
      if(tipo.type =="password"){
         tipo.type = "text";
      } else{
         tipo.type = "password";
        }
    }
</script>


          <div class="row row-lembrarsenha">
            <div class="col-xs-8" style="padding-left: 22px;">
              <div class="checkbox icheck" id="box-lembrar-senha">
                <label>
                  <input type="checkbox"> Lembrar senha
                </label>
              </div>
            </div>
          </div>
          <button type="submit" class="btn btn-primary btn-block btn-flat btn-login-custom">Login</button>
        <?=$this->Form->end();?>
        <hr />
        <div style="text-align: center;margin-top: -9px">
          <a href="<?=$this->Url->build(['action' => 'recoverPassword'])?>">Esqueceu a senha?</a><br><br><br><br>
          <a id="criar" href="<?=$this->Url->build(['action' => 'register'])?>" class="text-center register-login" style="text-decoration: underline" >Crie uma conta</a>
        </div>
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

<?php
use Cake\ORM\TableRegistry;
$usersTable = TableRegistry::get('Users');
$user = $this->request->getAttribute('identity');
$user = $usersTable->find()->where(['id' => $user->id])->all()->first();
if ($user && property_exists($user, 'img_profile')) {
  $user = $user->img_profile;
}
//if(empty($user)){
else {
  $user = "profile-default.png";
}
$imgProfileUser = "uploads/profile/".$user;

date_default_timezone_set('America/Sao_Paulo');
?>
<!DOCTYPE html>
<html>

<head><meta http-equiv="Content-Type" content="text/html; charset=utf-8">

  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title>NGProc</title>
  <!-- Tell the browser to be responsive to screen width -->
  <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
  <link rel="stylesheet" href="<?= $this->Url->build('css/Gotham/style.css') ?>">
  <!-- Bootstrap 3.3.7 -->
  <link rel="stylesheet" href="<?= $this->Url->build('bower_components/bootstrap/dist/css/bootstrap.min.css') ?>">
  <!-- Font Awesome -->
  <link rel="stylesheet" href="<?= $this->Url->build('bower_components/font-awesome/css/font-awesome.min.css') ?>">
  <!-- Ionicons -->
  <!-- Theme style -->
  <link rel="stylesheet" href="<?= $this->Url->build('dist/css/AdminLTE.min.css') ?>">
  <!-- AdminLTE Skins. Choose a skin from the css/skins
       folder instead of downloading all of them to reduce the load. -->
  <link rel="stylesheet" href="<?= $this->Url->build('dist/css/skins/_all-skins.min.css') ?>">
  <?= $this->Html->css('dashboard_custom') ?>
  <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
  <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
  <!--[if lt IE 9]>
  <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
  <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
  <![endif]-->

  <!-- Google Font -->
  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,600,700,300italic,400italic,600italic">

  <link rel="icon" href="<?= $this->Url->build('img/icons/logo.png') ?>" type="image/ico" />
  <link rel="shortcut icon" href="<?= $this->Url->build('img/icons/logo.png') ?>" type="image/x-icon" />

  <!-- jQuery 3 -->
  <script src="<?= $this->Url->build('bower_components/jquery/dist/jquery.min.js') ?>"></script>
  <!--Ajax-->
  <link rel="stylesheet" href="https://ajax.googleapis.com/ajax/libs/jqueryui/1.12.1/themes/smoothness/jquery-ui.css">

  <!-- Multiselect -->
  <link href="https://cdn.jsdelivr.net/npm/select2@4.0.12/dist/css/select2.min.css" rel="stylesheet" />

  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
  <style>
        /*ESTILIZANDO O MULTISELECT (PLUGIN SELECT2) DO CADASTRO DE PARCEIROS*/
        /* Caixa de seleção */
        .select2-container--default .select2-selection--multiple {
          width: 100%;
          max-height: 34px;
          overflow-y: auto;
        }

        /* Barra de rolagem da caixa de seleção */
        .select2-container--default .select2-selection--multiple::-webkit-scrollbar {
          display: none;
        }

        /* Caixa de seleção em foco*/
        .select2-container--default.select2-container--focus .select2-selection--multiple {
          padding: 0 10px;
        }

        /*Item escolhido localizado dentro da caixa de seleção*/
        .select2-container--default .select2-selection--multiple .select2-selection__choice {
          background-color: #367fa9;
        }

        /*Item escolhido marcado dentro da lista de seleção*/
        .select2-container--default .select2-results__option[aria-selected=true] {
          background-color: #367fa9;
          color: #fff;
        }

        /*Item ao passar o mouse por cima*/
        .select2-container--default .select2-results__option--highlighted[aria-selected] {
          background-color: #367fa9;
        }

        /* botão de excluir o item selecionado */
        .select2-container--default .select2-selection--multiple .select2-selection__choice__remove {
          color: #fff;
        }

        .display-none{
          display:none;
        }
        body{
          height: 100%
        }
        div.box-load{
          position:fixed;
          width:100%;
          height:100%;
          background: rgba(0,0,0,.5);
          z-index: 80000;

          display:flex;
          align-items: center;
          justify-content: center;
        }
        div.spinner{
          width: 60px;
          height: 60px;
          border: 8px solid #e8e8e8;
          border-left-color: rgb(0, 66, 104);;
          border-radius: 50%;
          animation: spin 1s linear infinite;
        }
        @keyframes spin {
          to {transform: rotate(360deg);}
        }
        </style>
    <!-- Link add a pedido do antonio e da gabi -->
    <!-- <script src="//code.jivosite.com/widget/YmAXnf1bcC" async></script> -->
    <!-- Global site tag (gtag.js) - Google Analytics -->
    <!-- <script async src="https://www.googletagmanager.com/gtag/js?id=UA-157059873-2"></script>
    <script>
      window.dataLayer = window.dataLayer || [];
      function gtag(){dataLayer.push(arguments);}
      gtag('js', new Date());

      gtag('config', 'UA-157059873-2');
    </script> -->
</head>

<body class="hold-transition skin-blue sidebar-mini">
  <div class="box-load">
        <div class="spinner"></div>
  </div>
  <script>$('.box-load').hide();</script>
  <!-- Site wrapper -->
  <div class="wrapper">

    <header class="main-header">
      <!-- Logo -->

      <!-- Header Navbar: style can be found in header.less -->
      <nav class="navbar navbar-static-top">
        <!-- Sidebar toggle button-->
        <a href="#" class="sidebar-toggle" data-toggle="push-menu" role="button">
          <div><?= $this->Html->image('icons/menu-responsivo.png') ?></div>
          <span class="sr-only">Toggle navigation</span>
          <span class="icon-bar"></span>
          <span class="icon-bar"></span>
          <span class="icon-bar"></span>
        </a>

        <div class="navbar-custom-menu">
          <ul class="nav navbar-nav">
            <li class="user dropdown user-menu">
              <a href="#" class="dropdown-toggle" data-toggle="dropdown" id="foto-perfil-dashboard" >
                <span class="nome-foto"><?= $userName ?></span>
                <img src="<?= $this->Url->build($imgProfileUser) ?>" class="user-image" alt="User Image">
              </a>
              <ul class="dropdown-menu">
                <li><a href="#">Perfil</a></li>
                <li><a href="#">Sair</a></li>
              </ul>
            </li>
          </ul>
        </div>
      </nav>
    </header>

    <!-- =============================================== -->

    <?= $this->element('Dashboard/sidebar'); ?>

    <!-- =============================================== -->

    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
      <!-- Content Header (Page header) -->
      <section class="content-header">
        <h1><?= $this->fetch('title') ?></h1>
      </section>

      <!-- Main content -->
      <section class="content">
        <?= $this->Flash->render() ?>
        <?= $this->fetch('content') ?>
        <!-- /.box -->

      </section>
      <!-- /.content -->
    </div>
    <!-- /.control-sidebar -->
    <!-- Add the sidebar's background. This div must be placed
       immediately after the control sidebar -->
    <div class="control-sidebar-bg"></div>
  </div>
  <!-- GOOGLE ANALyTICS -->
  <script>
    window.dataLayer = window.dataLayer || [];
    function gtag(){dataLayer.push(arguments);}
    gtag('js', new Date());

    gtag('config', 'AW-665648971');
  </script>
  <script>
    $(document).ready(function() {
      $('.sidebar-menu').tree()
    })
  </script>
  <!-- ./wrapper -->
  <!-- jQuery 3 -->
  <!--<script src="<?= $this->Url->build('bower_components/jquery/dist/jquery.min.js') ?>"></script>-->
  <!-- Bootstrap 3.3.7 -->
  <script src="<?= $this->Url->build('bower_components/bootstrap/dist/js/bootstrap.min.js') ?>"></script>
  <!-- PagSeguro -->
  <script type="text/javascript" src="https://stc.pagseguro.uol.com.br/pagseguro/api/v2/checkout/pagseguro.lightbox.js"></script>
  <!-- SlimScroll -->
  <script src="<?= $this->Url->build('bower_components/jquery-slimscroll/jquery.slimscroll.min.js') ?>"></script>
  <!-- FastClick -->
  <script src="<?= $this->Url->build('bower_components/fastclick/lib/fastclick.js') ?>"></script>
  <!-- AdminLTE App -->
  <script src="<?= $this->Url->build('dist/js/adminlte.js') ?>"></script>
  <!-- AdminLTE for demo purposes -->
  <script src="<?= $this->Url->build('dist/js/demo.js') ?>"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.11.0/umd/popper.min.js" integrity="sha384-b/U6ypiBEHpOf/4+1nzFpr53nxSS+GLCkfwBdFNTxtclqqenISfwAzpKaMNFNmj4" crossorigin="anonymous"></script>
  <!-- Global site tag (gtag.js) - Google Ads: 665648971 -->
  <script async src="https://www.googletagmanager.com/gtag/js?id=AW-665648971"></script>
  <!-- Multiselect - PLUGIN SELECT2-->
  <script src="https://cdn.jsdelivr.net/npm/select2@4.0.12/dist/js/select2.min.js"></script>
  <script>
    //ESCONDENDO O LOAD
    function carregarLoad(active){
        if(active){
            $('.box-load').show();
        }else{
            $('.box-load').hide();
        }
        // $('.box-load').toggleClass('box-load-active');
        // $('#load').toggleClass('load-active');
    }
  </script>
  <script>
    // Iniciando o plugin SELECT2 no elemeno que o utiliza
    $(document).ready(function() {
      $('.js-example-basic-single').select2();
    });
    //converte input para letras maiusculas
    function maiuscula(z) {
      v = z.value.toUpperCase();
      z.value = v;
    }
  </script>
  <!-- Hotjar Tracking Code for https://ngproc.com.br/ -->
  <!-- <script>
    (function(h,o,t,j,a,r){
      h.hj=h.hj||function(){(h.hj.q=h.hj.q||[]).push(arguments)};
      h._hjSettings={hjid:1641368,hjsv:6};
      a=o.getElementsByTagName('head')[0];
      r=o.createElement('script');r.async=1;
      r.src=t+h._hjSettings.hjid+j+h._hjSettings.hjsv;
      a.appendChild(r);
    })(window,document,'https://static.hotjar.com/c/hotjar-','.js?sv=');
  </script> -->
</body>

</html>

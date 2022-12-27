<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <?php echo $this->renderSection('style');?>
    <meta name="description" content="">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="robots" content="all,follow">
    <!-- Bootstrap CSS-->
    <link rel="stylesheet" href="<?php echo site_url('resources/');?>vendor/bootstrap/css/bootstrap.min.css">
    <!-- Font Awesome CSS-->
    <link rel="stylesheet" href="<?php echo site_url('resources/');?>vendor/font-awesome/css/font-awesome.min.css">
    <!-- Custom Font Icons CSS-->
    <link rel="stylesheet" href="<?php echo site_url('resources/');?>css/font.css">
    <!-- Google fonts - Muli-->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Muli:300,400,700">
    <!-- theme stylesheet-->
    <link rel="stylesheet" href="<?php echo site_url('resources/');?>css/style.default.css" id="theme-stylesheet">
    <!-- Custom stylesheet - for your changes-->
    <link rel="stylesheet" href="<?php echo site_url('resources/');?>css/custom.css">
    <!-- Favicon-->
    <link rel="shortcut icon" href="<?php echo site_url('resources/');?>img/favicon.ico">
    <!-- Tweaks for older IEs-->
    <!--[if lt IE 9]>
        <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script><![endif]-->

    <!-- Reservado para reinderizar o estilo de cada View -->
    <?php echo $this->renderSection('style');?>
</head>
  <body>
    <div class="login-page">
      <div class="container d-flex align-items-center">
        <div class="form-holder has-shadow">

          <!-- Espaço para ser reinderizado -->
          <?php echo $this->renderSection('content');?>

        

        </div>
      </div>
      <div class="copyrights text-center">
         <p>2018 &copy; Your company. Download From <a target="_blank" href="https://templateshub.net">Templates Hub</a>.</p>
      </div>
    </div>
    <!-- JavaScript files-->

    <script src="<?php echo site_url('resources/');?>vendor/jquery/jquery.min.js"></script>
    <script src="<?php echo site_url('resources/');?>vendor/jquery-validation/jquery.validate.min.js"></script>
    <script src="<?php echo site_url('resources/');?>vendor/popper.js/umd/popper.min.js"> </script>
    <script src="<?php echo site_url('resources/');?>vendor/bootstrap/js/bootstrap.min.js"></script>
    <script src="<?php echo site_url('resources/');?>js/front.js"></script>

    <!-- Reservado para reinderizar o scripts de cada View -->
    <?php echo $this->renderSection('scripts');?>
  </body>
</html>
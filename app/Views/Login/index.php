<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title> FR - | <?php echo $title;?> </title>
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
          <div class="row">
            <!-- Logo & Information Panel-->
            <div class="col-lg-6">
              <div class="info d-flex align-items-center">
                <div class="content">
                  <div class="logo">
                    <h1><?php echo $title;?></h1>
                  </div>
                  <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit.</p>
                </div>
              </div>
            </div>
            <!-- Form Panel    -->
            <div class="col-lg-6 bg-white">
              <div class="form d-flex align-items-center">
                <div class="content">
                    <?php echo form_open('/', ['id' => 'form', 'class' => 'form-validate']);?>
                    <div id="response"></div>
                    <div class="form-group">
                      <input id="login-username" type="text" name="email" required data-msg="Pr favor, informe seu e-mail." class="input-material">
                      <label for="login-username" class="label-material">Seu e-mail de acesso</label>
                    </div>
                    <div class="form-group">
                      <input id="login-password" type="password" name="password" required data-msg="Por favor, informe a sua senha." class="input-material">
                      <label for="login-password" class="label-material">Sua senha</label>
                    </div>
                    <input type="submit" id="btn-login" class="btn btn-primary" value="Entrar"/>
                    <!-- This should be submit button but I replaced it with <a> for demo purposes-->
                  </form><a href="#" class="forgot-pass mt-3">Esqueceu a sua senha?</a>
                  <?php echo form_close(); ?>
                </div>
              </div>
            </div>
          </div>
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

    <script>
$(document).ready(function() {
    $('#form').on('submit', function(e) {
        e.preventDefault();
        $.ajax({
            type: 'POST',
            url: '<?php echo site_url('login/create')?>',
            data: new FormData(this),
            dataType: 'json',
            contentType: false,
            cache: false,
            processData: false,
            beforeSend: function() {
                $("#response").html('');
                $("#btn-login").val('Por favor, aguarde...');
            },
            success: function(response) {
                $("#btn-login").val('Salvar');
                $("#btn-login").removeAttr('disabled');
                $('[name=csrf_ordem]').val(response.token);
                if (!response.erro) {
                    window.location.href =
                            "<?php echo site_url();?>" + response.redirect;
                }else{
                    // Erro de validação
                    $('#response').html('<div class="alert alert-danger">' + response.erro + '</div>');

                    if(response.errors_model){
                        $.each(response.errors_model, function(key, valeu){
                            $('#response').append('<ul class="list-unstyled"><li class="text-danger">'+ valeu +'</li></ul>')
                        });
                    }

                }
             

            },
            error: function() {
                alert(
                    'Não foi possível processar a solicitação. Por favor entre em contato com o suporte técnico.'
                    )
                $("#btn-login").val('Salvar');
                $("#btn-login").removeAttr('disabled');
            }
        });
    });
    $("form").submit(function(){
        $(this).find(":submit").attr('disabled', 'disabled');
    });
});
</script>

  </body>
</html>
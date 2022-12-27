<?php $this->extend('Layout/Auth/main-auth') ?>

<?php echo $this->section('title'); ?> <?php echo $title; ?> <?php echo $this->endSection(); ?>

<?php echo $this->section('style'); ?> 
  <!-- Aqui coloco os estilos -->
<?php echo $this->endSection(); ?>

<?php echo $this->section('content'); ?> 
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
            <?php echo $this->include('Layout/_message') ?>
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
<?php echo $this->endSection(); ?>


<?php echo $this->section('scripts'); ?> 
  <!-- Aqui coloco os scripts da View --> 
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
<?php echo $this->endSection(); ?>




<!--  -->
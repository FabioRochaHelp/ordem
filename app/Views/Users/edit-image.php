<?php $this->extend('Layout/main') ?>

<?php echo $this->section('title'); ?> <?php echo $title; ?> <?php echo $this->endSection(); ?>

<?php echo $this->section('style'); ?>
<!-- Aqui coloco os estilos -->
<?php echo $this->endSection(); ?>

<?php echo $this->section('content'); ?>

<div class="row">
    <div class="col-lg-6">
        <div class="block">
            <div class="block-body">
                <!-- Exibirá os retornos do BackEnd -->
                <div id="response">

                </div>
                <?php echo form_open_multipart('/', ['id' => 'form'], ['id' => "$user->id"])?>
                <div class="form-group">
                    <label class="form-control-label">Escolha uma imagem</label>
                    <input type="file" name="avatar" class="form-control">
                </div>
                <div class="form-group mt-5 mb-2">
                    <input id="btn-save" type="submit" value="Salvar" class="btn btn-danger btn-sm mr-s">
                    <a href="<?php echo site_url("users/load/$user->id");?>"
                        class="btn btn-secondary btn-sm ml-2">Voltar</a>
                </div>
                <?php echo form_close();?>
            </div>
        </div>
    </div>
</div>
<?php echo $this->endSection(); ?>

<?php echo $this->section('scripts'); ?>

<script>
$(document).ready(function() {
    $('#form').on('submit', function(e) {
        e.preventDefault();
        $.ajax({
            type: 'POST',
            url: '<?php echo site_url('users/upload')?>',
            data: new FormData(this),
            dataType: 'json',
            contentType: false,
            cache: false,
            processData: false,
            beforeSend: function() {
                $("#response").html('');
                $("#btn-save").val('Por favor, aguarde...');
            },
            success: function(response) {
                $("#btn-save").val('Salvar');
                $("#btn-save").removeAttr('disabled');
                $('[name=csrf_ordem]').val(response.token);
                if (!response.erro) {
                    //Tudo certo com a atualização do usuário
                    window.location.href =
                        "<?php echo site_url("users/load/$user->id");?>";
                } else {
                    // Erro de validação
                    $('#response').html('<div class="alert alert-danger">' + response.erro +
                        '</div>');

                    if (response.errors_model) {
                        $.each(response.errors_model, function(key, valeu) {
                            $('#response').append(
                                '<ul class="list-unstyled"><li class="text-danger">' +
                                valeu + '</li></ul>')
                        });
                    }

                }


            },
            error: function() {
                alert(
                    'Não foi possível processar a solicitação. Por favor entre em contato com o suporte técnico.'
                )
                $("#btn-save").val('Salvar');
                $("#btn-save").removeAttr('disabled');
            }
        });
    });
    $("form").submit(function() {
        $(this).find(":submit").attr('disabled', 'disabled');
    });
});
</script>

<?php echo $this->endSection(); ?>
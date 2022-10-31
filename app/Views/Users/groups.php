<?php $this->extend('Layout/main') ?>

<?php echo $this->section('title'); ?> <?php echo $title; ?> <?php echo $this->endSection(); ?>

<?php echo $this->section('style'); ?>
<style>
/* Estilizando o select para acompanhar a formatação do template */

.selectize-input,
.selectize-control.single .selectize-input.input-active {
    background: #2d3035 !important;
}

.selectize-dropdown,
.selectize-input,
.selectize-input input {
    color: #777;
}

.selectize-input {
    /*        height: calc(2.4rem + 2px);*/
    border: 1px solid #444951;
    border-radius: 0;
}
</style>

<link rel="stylesheet" type="text/css"
    href="<?php echo site_url('resources/vendor/selectize/selectize.bootstrap4.css')?>" />

<?php echo $this->endSection(); ?>

<?php echo $this->section('content'); ?>

<div class="row">
    <div class="col-lg-4">
        <div class="block">
            <?php if(empty($groupsAvailable)): ?>
            <p class="text-center mt-0">Esse usuário já faz parte de todos os grupos disponíveis!</p>
            <?php else: ?>
            <div id="response">

            </div>
            <?php echo form_open('/', ['id' => 'form'], ['id' => "$user->id"])?>
            <div class="form-group">
                <label class="form-control-label">Escolha uma ou mais pemissões</label>
                <select name="groups_id[]" multiple class="selectize">
                    <option value="">Escolha...</option>
                    <?php foreach ($groupsAvailable as $group): ?>
                    <option value="<?php echo $group->id;?>"><?php echo $group->groupname;?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="form-group mt-5 mb-2">
                <input id="btn-save" type="submit" value="Salvar" class="btn btn-danger btn-sm mr-s">
                <a href="<?php echo site_url("users/load/$user->id");?>"
                    class="btn btn-secondary btn-sm ml-2">Voltar</a>
            </div>
            <?php echo form_close();?>

            <?php endif; ?>

            <!-- Exibirá os retornos do BackEnd -->

        </div>
    </div>
    <div class="col-lg-8">
        <div class="block">

            <?php  if(empty($user->groups)):?>
            <p class="text-center text-warning mt-0">Esse usuário não faz parte de nenhum grupo de acesso!</p>
            <?php else :?>
            <div class="table-responsive">
                <table class="table table-bordered table-striped table-sm">
                    <thead>
                        <tr>
                            <th>Grupo de acesso</th>
                            <th>Descrição</th>
                            <th>Ação</th>
                        </tr>
                    </thead> 
                    <tbody>
                        <?php foreach ($user->groups as $group): ?>
                        <tr>
                            <td><?php echo $group->groupname;?></td>
                            <td><?php echo ellipsize($group->description, 32, .5) ;?></td>
                            <td>
                                <?php 
                                    $atrib = [
                                        'onSubmit' => "return confirm('Tem certeza da exclusão da pemissão?');",
                                    ];
                                ?>
                                <?php echo form_open("users/removegroups/$group->main_id", $atrib); ?>
                                      <button type="submit" class="btn btn-sm btn-danger">Excluir</button>
                                <?php echo form_close();?>
                            </td>
                        </tr>
                        <?php endforeach;?>
                    </tbody>
                </table>
                <div class="mt-3 ml-1">
                    <?php echo $user->pager->links();?>
                </div>
            </div>
            <?php endif;?>


        </div>
    </div>
</div>
<?php echo $this->endSection(); ?>

<?php echo $this->section('scripts'); ?>
<script type="text/javascript" src="<?php echo site_url('resources/vendor/selectize/selectize.min.js')?>"></script>

<script>
$(document).ready(function() {
    $(".selectize").selectize({
        create: true,
        sortField: "text",
    });

    $('#form').on('submit', function(e) {
        e.preventDefault();
        $.ajax({
            type: 'POST',
            url: '<?php echo site_url('users/savegroups')?>',
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
                        "<?php echo site_url("users/groups/$user->id");?>";
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
<?php $this->extend('Layout/main') ?>

<?php echo $this->section('title'); ?> <?php echo $title; ?> <?php echo $this->endSection(); ?>

<?php echo $this->section('style'); ?>
<!-- Aqui coloco os estilos -->
<?php echo $this->endSection(); ?>

<?php echo $this->section('content'); ?>

<div class="row">
    <div class="col-lg-4">
        <div class="block">

            <div class="text-center">
                <?php if($user->avatar == null):?>
                <img src="<?php echo site_url('resources/img/user_not_image.png')?>" class="card-img-top"
                    style="width: 90%" alt="Usuário Default">
                <?php else:?>
                <img src="<?php echo site_url("users/avatar/$user->avatar")?>" class="card-img-top" style="width: 90%"
                    alt="<?php echo esc($user->avatar);?>">
                <?php endif;?>

                <a href="<?php echo site_url("users/editimage/$user->id")?>"
                    class="btn btn-outline-primary btn-sm mt-3">Alterar imagem</a>
            </div>

            <hr class="border-secondary">
            <h5 class="card-title mt-2"><?php echo esc($user->name_user);?></h5>
            <p class="card-text"><?php echo esc($user->email);?></p>
            <p class="card-text">Usuário: <?php echo $user->getSituation();?></p>
            <p class="card-text">Criado <?php echo $user->created_at->humanize();?></p>
            <p class="card-text">Atualizado <?php echo $user->updated_at->humanize();?></p>

            <!-- Example single danger button -->
            <div class="btn-group">
                <button type="button" class="btn btn-danger dropdown-toggle" data-toggle="dropdown"
                    aria-expanded="false">
                    Ações
                </button>
                <div class="dropdown-menu">
                    <a class="dropdown-item" href="<?php echo site_url("users/edit/$user->id");?>">Editar usuário</a>
                    <a class="dropdown-item" href="<?php echo site_url("users/groups/$user->id");?>">Gerenciar grupos de acesso</a>
                    <div class="dropdown-divider"></div>

                    <?php if($user->deleted_at == null):?>
                    <a class="dropdown-item" href="<?php echo site_url("users/delete/$user->id");?>">Excluir usuário</a>
                    <?php else: ?>
                    <a class="dropdown-item" href="<?php echo site_url("users/restore/$user->id");?>">Restaurar usuário</a>
                    <?php endif; ?>
                </div>
            </div>

            <a href="<?php echo site_url("users");?>" class="btn btn-secondary ml-2">Voltar</a>

        </div>
    </div>
</div>
<?php echo $this->endSection(); ?>

<?php echo $this->section('scripts'); ?>
<!-- Aqui coloco os scripts da View -->
<?php echo $this->endSection(); ?>
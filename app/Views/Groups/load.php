<?php $this->extend('Layout/main') ?>

<?php echo $this->section('title'); ?> <?php echo $title; ?> <?php echo $this->endSection(); ?>

<?php echo $this->section('style'); ?>
<!-- Aqui coloco os estilos -->
<?php echo $this->endSection(); ?>

<?php echo $this->section('content'); ?>

<div class="row">
    <?php if($group->id < 3):?>
    <div class="col-md-12">

        <div class="alert alert-info" role="alert">
            <h4 class="alert-heading">Importante!</h4>
            <p>O Grupo <b><?php echo esc($group->groupname);?></b> não pode ser editado ou excluído, pois eles não pode
                ter suas permissões revogadas.</p>
            <hr>
            <p class="mb-0">Não se preocupe, pois os demais grupos podem ser editados ou excluídos se fizer necessário.
            </p>
        </div>
    </div>
    <?php endif; ?>
    <div class="col-lg-4">
        <div class="block">
            <h5 class="card-title mt-2"><?php echo esc($group->groupname);?></h5>
            <p class="card-text"><?php echo $group->getSituation();?>
                <?php if($group->deleted_at == null):?>
                <a tabindex="0" style="text-decoration: none;" class="" role="button" data-toggle="popover"
                    data-trigger="focus" title="Importante"
                    data-content="Esse grupo <?php echo ($group->disposition == true ? 'será' : 'não será'); ?> exibido como opção na hora de definir um <b>Responsável Técnico</b> pela Ordem de Serviço.">&nbsp;&nbsp;<i
                        class="fa fa-question-circle text-danger fa-lg"></i></a>
                <?php endif;?>
            </p>
            <p class="card-text"><?php echo esc($group->description);?></p>
            <p class="card-text">Criado <?php echo $group->created_at->humanize();?></p>
            <p class="card-text">Atualizado <?php echo $group->updated_at->humanize();?></p>

            <!-- Example single danger button -->
            <?php if($group->id > 2):?>
            <div class="btn-group mr-2">
                <button type="button" class="btn btn-danger dropdown-toggle" data-toggle="dropdown"
                    aria-expanded="false">
                    Ações
                </button>
                <div class="dropdown-menu">
                    <a class="dropdown-item" href="<?php echo site_url("groups/edit/$group->id");?>">Editar grupo de
                        acesso</a>

                    <?php if($group->id > 2) :?>
                    <a class="dropdown-item" href="<?php echo site_url("groups/permissions/$group->id");?>">Gerenciar as permissões do grupo</a>
                    <?php endif;?>
                    <div class="dropdown-divider"></div>

                    <?php if($group->deleted_at == null):?>
                    <a class="dropdown-item" href="<?php echo site_url("groups/delete/$group->id");?>">Excluir grupo de
                        acesso</a>
                    <?php else: ?>
                    <a class="dropdown-item" href="<?php echo site_url("groups/restore/$group->id");?>">Restaurar grupo
                        de acesso</a>
                    <?php endif; ?>
                </div>
            </div>
            <?php endif;?>

            <a href="<?php echo site_url("groups");?>" class="btn btn-secondary ml-2">Voltar</a>

        </div>
    </div>
</div>
<?php echo $this->endSection(); ?>

<?php echo $this->section('scripts'); ?>
<!-- Aqui coloco os scripts da View -->
<?php echo $this->endSection(); ?>
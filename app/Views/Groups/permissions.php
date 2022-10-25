<?php $this->extend('Layout/main') ?>

<?php echo $this->section('title'); ?> <?php echo $title; ?> <?php echo $this->endSection(); ?>

<?php echo $this->section('style'); ?>
<!-- Aqui coloco os estilos -->
<?php echo $this->endSection(); ?>

<?php echo $this->section('content'); ?>

<div class="row">
    <div class="col-lg-8"></div>
    <div class="col-lg-4">
        <div class="block">

            <?php  if(empty($group->permissions)):?>
            <p class="text-center text-warning mt-0">Esse grupo ainda não possui permissões de acesso!</p>
            <?php else :?>
            <div class="table-responsive">
                <table class="table table-bordered table-striped table-sm">
                    <thead>
                        <tr>
                            <th>Permissão</th>
                            <th>Ação</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($group->permissions as $permission) : ?>
                        <tr>
                            <td><?php echo $permission->permission;?></td>
                            <td><a href="#" class="btn btn-sm btn-danger">Excluir</td>
                        </tr>
                        <?php endforeach;?>
                    </tbody>
                </table>
            </div>
            <?php endif;?>


        </div>
    </div>
</div>
<?php echo $this->endSection(); ?>

<?php echo $this->section('scripts'); ?>
<!-- Aqui coloco os scripts da View -->
<?php echo $this->endSection(); ?>
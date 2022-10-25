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
                <?php echo form_open("users/delete/$user->id")?>
                <div class="alert alert-warning" role="alert">
                    Tem certeza da exclusão do registro?
                </div>
                <div class="form-group mt-5 mb-2">
                    <input id="btn-save" type="submit" value="Sim, pode excluir" class="btn btn-danger btn-sm mr-s">
                    <a href="<?php echo site_url("users/load/$user->id");?>"
                        class="btn btn-secondary btn-sm ml-2">Cancelar</a>
                </div>
                <?php echo form_close();?>
            </div>
        </div>
    </div>
</div>
<?php echo $this->endSection(); ?>

<?php echo $this->section('scripts'); ?>

<?php echo $this->endSection(); ?>
<?php $this->extend('Layout/main') ?>

<?php echo $this->section('title'); ?> <?php echo $title; ?> <?php echo $this->endSection(); ?>

<?php echo $this->section('style'); ?> 
  <!-- Aqui coloco os estilos -->
<?php echo $this->endSection(); ?>

<?php echo $this->section('content'); ?> 
  <!-- Aqui coloco o conteúdo da View --> 
<?php echo $this->endSection(); ?>

<?php echo $this->section('scripts'); ?> 
  <!-- Aqui coloco os scripts da View --> 
<?php echo $this->endSection(); ?>
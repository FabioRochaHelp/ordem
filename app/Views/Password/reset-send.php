<?php $this->extend('Layout/Auth/main-auth') ?>

<?php echo $this->section('title'); ?> <?php echo $title; ?> <?php echo $this->endSection(); ?>

<?php echo $this->section('style'); ?> 
  <!-- Aqui coloco os estilos -->
<?php echo $this->endSection(); ?>

<?php echo $this->section('content'); ?> 
<div class="row">
      <!-- Logo & Information Panel-->
      <div class="col-lg-8 mx-auto">
        <div class="info d-flex align-items-center">
          <div class="content">
            <div class="logo">
              <h1><?php echo $title;?></h1>
            </div>
            <p>Não deixe de conferir a caixa de span.</p>
          </div>
        </div>
      </div>
      <!-- Form Panel    -->
      <div class="col-lg-6 bg-white d-none">
        <div class="form d-flex align-items-center">
          <div class="content">
              
          </div>
        </div>
      </div>
    </div>
<?php echo $this->endSection(); ?>


<?php echo $this->section('scripts'); ?> 
  <!-- Aqui coloco os scripts da View --> 

<?php echo $this->endSection(); ?>




<!--  -->
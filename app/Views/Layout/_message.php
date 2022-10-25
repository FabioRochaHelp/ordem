<?php if(session()->has('success')): ?>

<div class="alert alert-success alert-dismissible fade show" role="alert">
    <strong>Sucesso!</strong> <?php echo session('success');?>
    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
        <span aria-hidden="true">&times;</span>
    </button>
</div>

<?php endif;?>

<?php if(session()->has('info')): ?>

<div class="alert alert-info alert-dismissible fade show" role="alert">
    <strong>Informação!</strong> <?php echo session('info');?>
    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
        <span aria-hidden="true">&times;</span>
    </button>
</div>

<?php endif;?>

<?php if(session()->has('alert')): ?>

<div class="alert alert-danger alert-dismissible fade show" role="alert">
    <strong>Atenção!</strong> <?php echo session('alert');?>
    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
        <span aria-hidden="true">&times;</span>
    </button>
</div>

<?php endif;?>

<?php if(session()->has('errors_model')): ?>
<ul>
    <?php foreach ($errors_model as $erro) : ?>

    <li class="text-danger"><?php echo $erro; ?></li>

    <?php endforeach; ?>
</ul>
<?php endif;?>

<!-- Utilizamos quando o formulário é interceptado, por erro do backend ou quando estamos fazendo um debug -->

<?php if(session()->has('error')): ?>

<div class="alert alert-danger alert-dismissible fade show" role="alert">
    <strong>Error!</strong> <?php echo session('error');?>
    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
        <span aria-hidden="true">&times;</span>
    </button>
</div>

<?php endif;?>
    <div class="form-group">
        <label class="form-control-label">Nome Completo</label>
        <input type="text" name="name_user" placeholder="Insira o nome completo" class="form-control"
            value="<?php echo esc($user->name_user);?>">
    </div>
    <div class="form-group">
        <label class="form-control-label">Email</label>
        <input type="email" name="email" placeholder="Insira o email de acesso" class="form-control"
            value="<?php echo esc($user->email);?>">
    </div>
    <div class="form-group">
        <label class="form-control-label">Senha</label>
        <input type="password" name="password" placeholder="Senha de acesso" class="form-control">
    </div>
    <div class="form-group">
        <label class="form-control-label">Confirmação de senha</label>
        <input type="password" name="password_confirmation" placeholder="Confirme a senha de acesso"
            class="form-control">
    </div>
    <div class="custom-control custom-checkbox">
        <input type="hidden" name="active" value="0">
        <input type="checkbox" name="active" value="1" class="custom-control-input" id="active"
            <?php if($user->active == true):?> checked <?php endif;?>>
        <label class="custom-control-label" for="active">Usuário Ativo</label>
    </div>
    <div class="form-group">
        <label class="form-control-label">Nome</label>
        <input type="text" name="groupname" placeholder="Insira o nome do grupo de acesso" class="form-control"
            value="<?php echo esc($group->groupname);?>">
    </div>
    <div class="form-group">
        <label class="form-control-label">Descrição</label>
        <textarea name="description" placeholder="Insira a descrição do grupo de acesso"
            class="form-control"><?php echo esc($group->description);?></textarea>
    </div>
    <div class="custom-control custom-checkbox">
        <input type="hidden" name="disposition" value="0">
        <input type="checkbox" name="disposition" value="1" class="custom-control-input" id="active"
            <?php if($group->disposition == true):?> checked <?php endif;?>>

        <label class="custom-control-label" for="active">Exibir grupo de acesso</label>
        <a tabindex="0" style="text-decoration: none;" class="" role="button" data-toggle="popover" data-trigger="focus"
            title="Importante"
            data-content="Se esse grupo for definido como <b class='text-danger'>Exibir grupo de acesso</b> ele será mostrado como opção na hora de definir um <b>Responsável Técnico</b> pela ordem de serviço.">&nbsp;&nbsp;<i
                class="fa fa-question-circle text-danger fa-lg"></i>
        </a>
    </div>
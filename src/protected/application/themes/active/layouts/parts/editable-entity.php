<?php 
if (!is_editable() && !$entity->canUser('modify')) 
    return; 
$can_edit_roles = $this->controller->id == 'agent' && $entity->user->id != $app->user->id && $entity->id == $entity->user->profile->id && $entity->user->canUser('addRole');
if(is_editable()){
    $classes = 'editable-entity-edit';
    if($can_edit_roles)
        $classes .= ' can-edit-roles';
}else{
    $classes = 'editable-entity-single';
}
?>

<div id="editable-entity" class="clearfix sombra <?php echo $classes ?>" data-action="<?php echo $action; ?>" data-entity="<?php echo $this->controller->id ?>" data-id="<?php echo $entity->id ?>" data-submit-button-selector="#submitButton">
    <h1 id="logo-spcultura-peq"><a href="<?php echo $app->getBaseUrl() ?>"><img src="<?php echo $assetURL ?>/img/logo-spcultura.png" /></a></h1>
    <?php if (is_editable()): ?>
        <script type="text/javascript">
            MapasCulturais.Messages.help('Os ícones de lápis indicam conteúdos editáveis.');
        </script>
        <div class="controles">
            <?php if ($can_edit_roles): ?>
                <div id="funcao-do-agente" class="dropdown">
                    <div class="placeholder js-selected"> <?php
                        if ($entity->user->is('superAdmin'))
                            echo '<span data-role="superAdmin">' . $app->getRoleName('superAdmin') . '</span>';
                        elseif ($entity->user->is('admin'))
                            echo '<span data-role="admin">' . $app->getRoleName('admin') . '</span>';
                        elseif ($entity->user->is('staff'))
                            echo '<span data-role="staff">' . $app->getRoleName('staff') . '</span>';
                        else
                            echo "<span>Normal</span>";
                        ?> </div>
                    <div class="submenu-dropdown js-options">
                        <ul>
                            <li>
                                <span>Normal</span>
                            </li>
                            <?php if ($entity->user->canUser('addRoleStaff')): ?>
                                <li data-role="staff">
                                    <span><?php echo $app->getRoleName('staff') ?></span>
                                </li>
                            <?php endif; ?>

                            <?php if ($entity->user->canUser('addRoleAdmin')): ?>
                                <li data-role="admin">
                                    <span><?php echo $app->getRoleName('admin') ?></span>
                                </li>
                            <?php endif; ?>

                            <?php if ($entity->user->canUser('addRoleSuperAdmin')): ?>
                                <li data-role="superAdmin">
                                    <span><?php echo $app->getRoleName('superAdmin') ?></span>
                                </li>
                            <?php endif; ?>

                        </ul>
                    </div>
                </div>
            <?php endif; ?>
            <!-- se estiver na página de edição e logado mostrar:-->
            <a id="submitButton" class="button  button--positive" data-legend="Salvar" data-legend-submitted="salvo">Salvar</a>
            <?php if ($this->controller->action !== 'create'): ?>
                <a href="<?php echo $this->controller->createUrl('single', array($entity->id)) ?>" class="button  button--negative">Cancelar</a>
            <?php endif; ?>
        </div>
    <?php elseif ($entity->canUser('modify')): ?>
        <script type="text/javascript">
            MapasCulturais.Messages.alert('Você possui permissão para editar este <?php echo strtolower($entity->entityType) ?>. Use os botões à direita para editar ou excluir.');
        </script>
        <div class="controles">
            <!-- se estiver na página comum e logado mostrar:-->
            <a href="<?php echo $entity->editUrl ?>" class="button">Editar</a>

            <?php if ($entity->canUser('remove') && $entity->status > 0): ?>
                <a href="<?php echo $entity->deleteUrl ?>" class="button  button--negative">Excluir</a>
            <?php elseif ($entity->canUser('undelete') && $entity->status < 0): ?>
                <a href="<?php echo $entity->undeleteUrl ?>" class="button  button--positive">Recuperar</a>
            <?php endif; ?>
        </div>
    <?php endif; ?>
</div>

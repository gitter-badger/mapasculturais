<?php
$action = preg_replace("#^(\w+/)#", "", $this->template);
$this->bodyProperties['ng-app'] = "Entity";

if(is_editable()){
    add_entity_types_to_js($entity);
    add_taxonoy_terms_to_js('area');

    add_taxonoy_terms_to_js('tag');

    add_entity_properties_metadata_to_js($entity);
}
add_map_assets();

add_agent_relations_to_js($entity);
add_angular_entity_assets($entity);

?>
<?php $this->part('editable-entity', array('entity'=>$entity, 'action'=>$action));  ?>

<div class="sidebar-left sidebar agente">
    <div class="setinha"></div>
    <?php $this->part('verified', array('entity' => $entity)); ?>
    <?php $this->part('widget-areas', array('entity'=>$entity)); ?>
    <?php $this->part('widget-tags', array('entity'=>$entity)); ?>
    <?php $this->part('redes-sociais', array('entity'=>$entity)); ?>
</div>
<article class="main-content agente">
    <header class="main-content-header">

        <div
            <?php if($header = $entity->getFile('header')): ?>
                style="background-image: url(<?php echo $header->transform('header')->url; ?>);" class="imagem-do-header com-imagem js-imagem-do-header"
            <?php elseif(is_editable()): ?>
                class="imagem-do-header js-imagem-do-header"
            <?php endif; ?>
        >
        <?php if(is_editable()): ?>
            <a class="botao editar js-open-editbox" data-target="#editbox-change-header" href="#">editar</a>
            <div id="editbox-change-header" class="js-editbox mc-bottom" title="Editar Imagem da Capa">
                <?php add_ajax_uploader ($entity, 'header', 'background-image', '.js-imagem-do-header', '', 'header'); ?>
            </div>
        <?php endif; ?>
        </div>
        <!--.imagem-do-header-->
        <div class="content-do-header">
            <?php if($avatar = $entity->avatar): ?>
            <div class="avatar com-imagem">
                    <img src="<?php echo $avatar->transform('avatarBig')->url; ?>" alt="" class="js-avatar-img" />
                <?php else: ?>
                    <div class="avatar">
                        <img class="js-avatar-img" src="<?php $this->asset('img/avatar--agent.png'); ?>" />
            <?php endif; ?>
                <?php if(is_editable()): ?>
                    <a class="botao editar js-open-editbox" data-target="#editbox-change-avatar" href="#">editar</a>
                    <div id="editbox-change-avatar" class="js-editbox mc-right" title="Editar avatar">
                        <?php add_ajax_uploader ($entity, 'avatar', 'image-src', 'div.avatar img.js-avatar-img', '', 'avatarBig'); ?>
                    </div>
                <?php endif; ?>
            </div>
            <!--.avatar-->
            <div class="entity-type agent-type">
                <div class="icone icon_profile"></div>
                <a href="#" class='js-editable-type' data-original-title="Tipo" data-emptytext="Selecione um tipo" data-entity='agent' data-value='<?php echo $entity->type ?>'>
                    <?php echo $entity->type->name; ?>
                </a>
            </div>
            <!--.entity-type-->
            <h2><span class="js-editable" data-edit="name" data-original-title="Nome de exibição" data-emptytext="Nome de exibição"><?php echo $entity->name; ?></span></h2>
        </div>
    </header>
    <ul class="abas clearfix clear">
        <li class="active"><a href="#sobre">Sobre</a></li>
        <li><a href="#agenda">Agenda</a></li>
    </ul>
    <div id="sobre" class="aba-content">
        <div class="ficha-spcultura">
            <?php if(is_editable() && $entity->shortDescription && strlen($entity->shortDescription) > 400): ?>
                <div class="mensagem alerta">O limite de caracteres da descrição curta foi diminuido para 400, mas seu texto atual possui <?php echo strlen($entity->shortDescription) ?> caracteres. Você deve alterar seu texto ou este será cortado ao salvar.</div>
            <?php endif; ?>

            <p>
                <span class="js-editable" data-edit="shortDescription" data-original-title="Descrição Curta" data-emptytext="Insira uma descrição curta" data-showButtons="bottom" data-tpl='<textarea maxlength="400"></textarea>'><?php echo is_editable() ? $entity->shortDescription : nl2br($entity->shortDescription); ?></span>
            </p>
            <div class="servico">

                <?php if(is_editable() || $entity->site): ?>
                    <p><span class="label">Site:</span>
                    <?php if(is_editable()): ?>
                        <span class="js-editable" data-edit="site" data-original-title="Site" data-emptytext="Insira a url de seu site"><?php echo $entity->site; ?></span></p>
                    <?php else: ?>
                        <a class="url" href="<?php echo $entity->site; ?>"><?php echo $entity->site; ?></a>
                    <?php endif; ?>
                <?php endif; ?>


                <?php if(is_editable()): ?>
                    <p class="privado"><span class="icone icon_lock"></span><span class="label">Nome:</span> <span class="js-editable" data-edit="nomeCompleto" data-original-title="Nome Completo ou Razão Social" data-emptytext="Insira seu nome completo ou razão social"><?php echo $entity->nomeCompleto; ?></span></p>
                    <p class="privado"><span class="icone icon_lock"></span><span class="label">CPF/CNPJ:</span> <span class="js-editable" data-edit="documento" data-original-title="CPF/CNPJ" data-emptytext="Insira o CPF ou CNPJ com pontos, hífens e barras"><?php echo $entity->documento; ?></span></p>
                    <p class="privado"><span class="icone icon_lock"></span><span class="label">Idade/Tempo:</span> <span class="js-editable" data-edit="idade" data-original-title="Idade/Tempo" data-emptytext="Insira sua idade ou tempo de existência"><?php echo $entity->idade; ?></span></p>
                    <p class="privado"><span class="icone icon_lock"></span><span class="label">Gênero:</span> <span class="js-editable" data-edit="genero" data-original-title="Gênero" data-emptytext="Selecione o gênero se for pessoa física"><?php echo $entity->genero; ?></span></p>
                    <p class="privado"><span class="icone icon_lock"></span><span class="label">Email:</span> <span class="js-editable" data-edit="emailPrivado" data-original-title="Email Privado" data-emptytext="Insira um email que não será exibido publicamente"><?php echo $entity->emailPrivado; ?></span></p>
                <?php endif; ?>

                <?php if(is_editable() || $entity->telefonePublico): ?>
                <p><span class="label">Telefone Público:</span> <span class="js-editable js-mask-phone" data-edit="telefonePublico" data-original-title="Telefone Público" data-emptytext="Insira um telefone que será exibido publicamente"><?php echo $entity->telefonePublico; ?></span></p>
                <?php endif; ?>

                <?php if(is_editable()): ?>
                <p class="privado"><span class="icone icon_lock"></span><span class="label">Telefone 1:</span> <span class="js-editable js-mask-phone" data-edit="telefone1" data-original-title="Telefone Privado" data-emptytext="Insira um telefone que não será exibido publicamente"><?php echo $entity->telefone1; ?></span></p>
                <p class="privado"><span class="icone icon_lock"></span><span class="label">Telefone 2:</span> <span class="js-editable js-mask-phone" data-edit="telefone2" data-original-title="Telefone Privado" data-emptytext="Insira um telefone que não será exibido publicamente"><?php echo $entity->telefone2; ?></span></p>
                <?php endif; ?>
            </div>


            <?php $lat = $entity->location->latitude; $lng = $entity->location->longitude; ?>
            <?php if ( is_editable() || ($entity->precisao && $lat && $lng) ): ?>
                <!--.servico-->
                <div class="servico clearfix">
                    <div class="infos">

                        <?php if(is_editable()): ?>
                            <p class="privado">
                                <span class="icone icon_lock"></span><span class="label">Localização:</span>
                                <span class="js-editable" data-edit="precisao" id="map-precisionOption" data-onchange="precisionChange" data-truevalue="Precisa"><?php echo $entity->precisao; ?></span>
                            </p>
                        <?php else: ?>
                            <span style="display:none" id="map-precisionOption" data-truevalue="Precisa"><?php echo $entity->precisao; ?></span>
                        <?php endif; ?>

                        <p><span class="label">Endereço:</span> <span class="js-editable" data-edit="endereco" data-original-title="Endereço" data-emptytext="Insira o endereço, se optar pela localização aproximada, informe apenas o CEP" data-showButtons="bottom"><?php echo $entity->endereco ?></span></p>
                        <p><span class="label">Distrito:</span> <span class="js-sp_distrito"><?php echo $entity->sp_distrito; ?></span></p>
                        <p><span class="label">Subprefeitura:</span> <span class="js-sp_subprefeitura"><?php echo $entity->sp_subprefeitura; ?></span></p>
                        <p><span class="label">Zona:</span> <span class="js-sp_regiao"><?php echo $entity->sp_regiao; ?></p>
                    </div>
                    <!--.infos-->
                    <div class="mapa">
                        <?php if(is_editable()): ?>
                            <button id="buttonLocateMe" class="btn btn-small btn-success" >Localize-me</button>
                        <?php endif; ?>
                        <div id="map" class="js-map" data-lat="<?php echo $lat?>" data-lng="<?php echo $lng?>">
                        </div>
                        <button id="buttonSubprefs" class="btn btn-small btn-success" ><i class="icon-map-marker"></i>Mostrar Subprefeituras</button>
                        <button id="buttonSubprefs_off" class="btn btn-small btn-danger" ><i class="icon-map-marker"></i>Esconder Subprefeituras</button>
                        <?php if(is_editable()): ?>
                        <script>
                            $('input[name="map-precisionOption"][value="<?php echo $entity->precisao; ?>"]').attr('checked', true);
                        </script>
                    <?php endif; ?>
                        <input type="hidden" id="map-target" data-name="location" class="js-editable" data-edit="location" data-value="[0,0]"/>
                    </div>
                    <!--.mapa-->
                </div>
                <!--.servico-->
            <?php endif; ?>

        </div>
        <!--.ficha-spcultura-->

        <?php if ( is_editable() || $entity->longDescription ): ?>
            <h3>Descrição</h3>
            <span class="descricao js-editable" data-edit="longDescription" data-original-title="Descrição do Agente" data-emptytext="Insira uma descrição do agente" ><?php echo is_editable() ? $entity->longDescription : nl2br($entity->longDescription); ?></span>
        <?php endif; ?>
        <!--.descricao-->
        <!-- Video Gallery BEGIN -->
            <?php $this->part('video-gallery.php', array('entity'=>$entity)); ?>
        <!-- Video Gallery END -->
        <!-- Image Gallery BEGIN -->
            <?php $this->part('gallery.php', array('entity'=>$entity)); ?>
        <!-- Image Gallery END -->
    </div>
    <!-- #sobre -->
    <div id="agenda" class="aba-content lista">
        <?php $this->part('agenda', array('entity' => $entity)); ?>
    </div>
    <!-- #agenda -->
    <?php $this->part('owner', array('entity' => $entity, 'owner' => $entity->owner)); ?>
</article>
<div class="sidebar agente sidebar-right">
    <div class="setinha"></div>
    <?php if($this->controller->action == 'create'): ?>
        <div class="widget">Para adicionar arquivos para download ou links, primeiro é preciso salvar o agente.</div>
    <?php endif; ?>

    <!-- Related Agents BEGIN -->
        <?php $this->part('related-agents.php', array('entity'=>$entity)); ?>
    <!-- Related Agents END -->

    <?php if(count($entity->spaces) > 0): ?>
    <div class="widget">
        <h3>Espaços do agente</h3>
        <ul class="widget-list js-slimScroll">
            <?php foreach($entity->spaces as $space): ?>
            <li class="widget-list-item"><a href="<?php echo $app->createUrl('space', 'single', array('id' => $space->id)) ?>"><span><?php echo $space->name; ?></span></a></li>
            <?php endforeach; ?>
        </ul>
    </div>
    <?php endif; ?>
    <!--
    <div class="widget">
        <h3>Projetos do agente</h3>
        <ul>
            <li><a href="#">Projeto 1</a></li>
            <li><a href="#">Projeto 2</a></li>
            <li><a href="#">Projeto 3</a></li>
        </ul>
    </div>
    -->

    <!-- Downloads BEGIN -->
        <?php $this->part('downloads.php', array('entity'=>$entity)); ?>
    <!-- Downloads END -->

    <!-- Link List BEGIN -->
        <?php $this->part('link-list.php', array('entity'=>$entity)); ?>
    <!-- Link List END -->
</div>

<?php $this->part('panel/part-nav.php')?>
<div class="lista-sem-thumb main-content">
	<header class="header-do-painel clearfix">
		<h2 class="alignleft">Meus Eventos</h2>
		<a class="button  button--positive" href="<?php echo $app->createUrl('event', 'create'); ?>">Adicionar Evento</a>
	</header>
    <ul class="abas clearfix clear">
        <li class="active"><a href="#ativos">Ativos</a></li>
        <li><a href="#lixeira">Lixeira</a></li>
    </ul>
    <div id="ativos">
        <?php foreach($user->enabledEvents as $entity): ?>
            <?php $this->part('panel/part-event', array('entity' => $entity)); ?>
        <?php endforeach; ?>
    </div>
    <!-- #ativos-->
    <div id="lixeira">
        <?php foreach($user->trashedEvents as $entity): ?>
            <?php $this->part('panel/part-event', array('entity' => $entity)); ?>
        <?php endforeach; ?>
    </div>
</div>

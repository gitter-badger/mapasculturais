<?php $this->part('panel/part-nav.php')?>
<div class="lista-sem-thumb main-content">
	<header class="header-do-painel clearfix">
		<h2 class="alignleft">Meus Espaços</h2>
		<a class="button  button--positive" href="<?php echo $app->createUrl('space', 'create'); ?>">Adicionar Espaço</a>
	</header>
    <ul class="abas clearfix clear">
        <li class="active"><a href="#ativos">Ativos</a></li>
        <li><a href="#lixeira">Lixeira</a></li>
    </ul>
    <div id="ativos">
        <?php foreach($user->enabledSpaces as $entity): ?>
            <?php $this->part('panel/part-space', array('entity' => $entity)); ?>
        <?php endforeach; ?>
    </div>
    <!-- #ativos-->
    <div id="lixeira">
        <?php foreach($user->trashedSpaces as $entity): ?>
            <?php $this->part('panel/part-space', array('entity' => $entity)); ?>
        <?php endforeach; ?>
    </div>
</div>

<h1 id="nom_categorie"><?=$categorie->getNom();?></h1>
<h2><?= $categorie->getDescription(); ?></h2>
<?php
    if(\App\Core\Security::canAsAdmin()):
?>
<a href="/categorie/update?categorie_id=<?=$categorie->getId()?>">Modifier</a>
<?php
endif;
if(!empty($categorie->getParent())):
?>
    <a href="categorie?categorie_id=<?= $categorie->getParent()->getId() ?>"><?= $categorie->getParent()->getNom() ?></a>
<?php endif; ?>

<div id="list_pages">

</div>
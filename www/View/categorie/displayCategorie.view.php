<h1><?= $categorie->getNom(); ?></h1>
<h2><?= $categorie->getDescription(); ?></h2>
<?php
if(!empty($categorie->getParent())):
?>
    <a href="categorie?categorie_id=<?= $categorie->getParent()->getId() ?>"><?= $categorie->getParent()->getNom() ?></a>
<?php endif; ?>
<h3></h3>

<?php
if(!empty($pages)){
    include('View/page/listPages.view.php');
}
?>

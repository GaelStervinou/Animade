<head>
    <meta charset="utf-8">
    <title>Dashboard</title>
    <link rel="stylesheet" type="text/css" href="dist/main.css">
</head>
<h1><?php use App\Core\Security;

    echo $personnage->getNom(); ?></h1>

<img src="<?= $personnage->getMedia()->getChemin()?>" alt="Image de l'article"
     width="300"
     height="300" >
<?php
    if(Security::isAdmin()):
?>
        <a href="/personnage/update?personnage_id=<?= $personnage->getId() ?>">Modifier</a>
<?php
    endif;
?>

<div id="list_pages">

</div>
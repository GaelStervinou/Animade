<head>
    <meta charset="utf-8">
    <title>Dashboard</title>
    <link rel="stylesheet" type="text/css" href="dist/main.css">
</head>
<h1><?php use App\Core\Security;

    echo $personnage->getNom();

    if($personnage->hasMedia() === true):
    ?></h1>

<img src="<?= $personnage->getMedia()->getChemin()?>" alt="Image du personnage"
     width="300"
     height="300" >
<?php
endif;
    if(Security::canAsAdmin()):
?>
        <a href="/personnage/update?personnage_id=<?= $personnage->getId() ?>">Modifier</a>
<?php
    endif;
?>

<div id="list_pages">

</div>
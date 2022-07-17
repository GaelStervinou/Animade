<main>
    <h1 id="nom_personnage"><?=$personnage->getNom();?></h1>
    <?php use App\Core\Security;


    if($personnage->hasMedia() === true):
        ?>
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
</main>
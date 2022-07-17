<main>
    <h1 id="nom_personnage"><?=$user->getFullName();?></h1>
    <?php use App\Core\Security;


    if($user->hasMedia() === true):
        ?>
        <img src="../<?=$user->getMedia()->getChemin()?>" alt="Image de l'utilisateur"
             width="300"
             height="300" >
    <?php
    endif;
    ?>
        <a href="/user/update?user_id=<?= $user->getId() ?>">Modifier</a>

    <div id="list_pages">

    </div>
</main>
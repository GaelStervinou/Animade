<main>
    <h1><?=$user->getFullName();?></h1>
    <?php


    if($user->hasMedia() === true):
        ?>
        <img src="../<?=$user->getMedia()->getChemin()?>" alt="Image de l'utilisateur"
             width="300"
             height="300" >
    <?php
    endif;
    ?>
    <a href="/user/update?user_id=<?= $user->getId() ?>">Modifier</a>
    <a href="/user/delete?user_id=<?= $user->getId() ?>">Clôturer le compte</a>
</main>
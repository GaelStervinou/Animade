<main>
    <section id="login-form">
        <div class="grid">

            <h1 id="nom_chapitre"><?=$chapitre->getTitre();?></h1>
            <?php use App\Core\Security;

            if($chapitre->hasMedia() === true):
            ?>

            <img src="../<?= $chapitre->getMedia()->getChemin()?>" alt="Image du chapitre"
            width="300"
            height="300" >
            <?php
            endif;
            if(Security::canAsAdmin()):
            ?>
            <a href="/chapitre/update?chapitre_id=<?= $chapitre->getId() ?>">Modifier</a>
            <?php
            endif;
            ?>

            <div id="list_pages">

            </div>
 
        </div>
    </section>
</main>

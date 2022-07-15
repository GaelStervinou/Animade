<main>
    <section id="login-form">
        <div class="grid">

            <h1><?php use App\Core\Security;

            echo $chapitre->getTitre();
            if($chapitre->hasMedia() === true):
            ?></h1>

            <img src="<?= $chapitre->getMedia()->getChemin()?>" alt="Image du chapitre"
            width="300"
            height="300" >
            <?php
            endif;
            if(Security::canAsAdmin()):
            ?>
            <a href="/personnage/update?personnage_id=<?= $chapitre->getId() ?>">Modifier</a>
            <?php
            endif;
            ?>

            <div id="list_pages">

            </div>
 
        </div>
    </section>
</main>

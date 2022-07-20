<main>
    <nav>
        <ul>
            <li><a href="/categorie/categories">Catégories</a></li>
            <li><a href="/chapitre/chapitres">Chapitres</a></li>
            <li><a href="/personnage/personnages">Personnages</a></li>
            <li><a href="/auteurs">Auteurs</a></li>
        </ul>
    </nav>
    <h3>Les derniers articles</h3>
    <?php
    include('View/page/listpages.view.php');
    ?>

    <?php
    if(!empty($lastChapitre)):
    ?>
    <h3>Le dernier chapitre " <?=$lastChapitre->getTitre()?> " est sorti!</h3>
    <p>Ne manquez pas les articles sur le <a href="/page/pages?chapitre_id=<?=$lastChapitre->getId()?>">sujet</a></p>

    <?php
    endif;
    if(!empty($mostLikedPage)):
        ?>
        <h3>Et l'auteur le plus liké de la semaine est..........</h3>

        <h4><a href="/page/pages?auteur_id=<?=$mostLikedPage->getAuteurId()?>"><?=$mostLikedPage->getAuteur()->getFullName()?></a> !</h4>
        <h4>Pour son superbe article <a href="/page?page=<?=$mostLikedPage->getSlug()?>"><?= $mostLikedPage->getTitre()?></a></h4>
        <p><?=$mostLikedPage->getDescription()?></p>

        <p>Bravo à lui!</p>

    <?php
    endif;
    echo $test;
    ?>
</main>
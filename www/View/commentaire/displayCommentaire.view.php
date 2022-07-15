<main>
    <div style="border: dashed red;">
        <?=$commentaire->getContenu()?>
        <?=$commentaire->getAuteur()->getFullName()?>
        <?php
        if($commentaire->hasMedia()):
            ?>
            <img src="<?= $commentaire->getMedia()->getChemin()?>" alt="Image de l'article"
                 width="100"
                 height="150" >
        <?php
        endif;
        if($commentaire->getStatut() == 2):
            ?>
            <a href="commentaire/delete?commentaire_id=<?= $commentaire->getId()?>">Supprimer</a>
        <?php
        else:
            ?>
            <p>Commentaire supprimé</p>
        <?php
        endif;
        ?>
    </div>
</main>
<head>
    <meta charset="utf-8">
    <title>Dashboard</title>
    <link rel="stylesheet" type="text/css" href="dist/main.css">
</head>
<h1><?php echo $page->getTitre(); ?></h1>
<h2><?php echo $page->getDescription(); ?></h2>

<?php
    if($can_edit == "yes"):
?>
    <h3>
        <a href="/page/update?page_id=<?= $page->getId()?>">Modifier la page</a>
    </h3>
<?php
    endif;
?>

<h3>
    
</h3>

<div>
    <?= $page->getContenu();?>
</div>
<?php
    if($page->hasMedia()):
?>
<div>

    <img src="<?= $page->getMedia()->getChemin()?>" alt="Image de l'article"
         width="100"
         height="150" >
</div>
<?php
    endif; ?>
<div>
    By : <?php echo $page->getAuteur()->getFullName();
    ?>
</div>

<div>
    <?php echo $page->getDateCreation()
    ?>
</div>

<div>
    <br>
    <br>
    <br>
    <br>
    Commentaires :
    <?php
        foreach($page->getCommentaires() as $commentaire):
    ?>
        <div>
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
                if(\App\Core\Security::displayEditButton($commentaire)):
            ?>
<a href="/commentaire/update?commentaire_id=<?= $commentaire->getId()?>">Modifier</a>
            <?php
                endif;
                if(\App\Core\Security::isUser()):
            ?>
                    <a href="#" class="commentaire_response" data-auteur="<?=$commentaire->getAuteur()->getFullName()?>" data-id="<?=$commentaire->getId()?>">Répondre</a>
            <?php
                endif;
            ?>
        </div>
    <?php
        endforeach;
        ?>
    <br>
    <br>
    <br>
    <br>
</div>
<div id="commentaire">
    <?php
    if($can_comment === "yes"):
        ?>
        <a href="#" hidden id="comment_page">Répondre à l'article</a>
    <?php
        $this->includePartial('form', $commentaire->getFormNewCommentaire());
    endif;
    ?>
    <input type="hidden" name="page_id" value="<?=$page->getId()?>">
    <input type="hidden" id="commentaire_id" name="commentaire_id" value="">
</div>

<script>
    window.onload = function(){
        var commentaire = document.querySelectorAll('.commentaire_response');
        for (var i = 0 ; i < commentaire.length; i++) {
            commentaire[i].addEventListener("click", loadCommentaireId, false);
        }
        document.querySelector('#comment_page').addEventListener("click",
            commentPage, false);
    }

    function loadCommentaireId(e){
        e.preventDefault();
        var titreFormulaire = document.querySelector('form');
        titreFormulaire.childNodes[1].textContent = 'Répondre à '+e.target.dataset.auteur;
        document.querySelector('#commentaire_id').setAttribute('value', e.target.dataset.id);
        document.querySelector('#comment_page').removeAttribute('hidden');

    }

    function commentPage(e){
        e.preventDefault();
        var titreFormulaire = document.querySelector('form');
        titreFormulaire.childNodes[1].textContent = 'Répondre à l\'article';
        document.querySelector('#commentaire_id').setAttribute('value', '');
        document.querySelector('#comment_page').setAttribute('hidden', '');
    }
</script>
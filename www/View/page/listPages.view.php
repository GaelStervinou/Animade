<meta charset="utf-8">
<?php
if(!empty($pages)):
    if(!empty($titre)):
?>
        <h2><?=$titre?></h2>
        <?php
        endif;
    if(!empty($recherche)): ?>
        <div>
            <h2>
                Résultat pour :
            </h2>
            <p>                <?= $recherche ?>
            </p>
        </div>
<?php
    endif;
?>
<table id="table_id" class="display" style="width: 1200px;">
    <thead>
    <tr>
        <th>Catégorie</th>
        <th>Titre</th>
        <th>Description</th>
        <th>Personnage</th>
        <th>Chapitre</th>
        <th>Auteur</th>
        <th>Date de publication</th>
    </tr>
    </thead>
    <tbody>
    <?php
        foreach ($pages as $page):
        ?>
        <tr style="text-align: center">
            <td><a href="/categorie?categorie_ id=<?= $page->getCategorieId()?>"><?= $page->getCategorie()->getNom()?></a></td>
            <td><a href="/page?page=<?= $page->getSlug()?>"><?= $page->getTitre()?></a></td>
            <td><?= substr($page->getDescription(), 0, 40)?></td>
            <td>
                <?php
                    if(!empty($page->getPersonnageId())): ?>
                        <a href="/personnage?personnage_id=<?= $page->getPersonnageId()?>"><?= $page->getPersonnage()->getNom()?></a>
                <?php endif; ?>
            </td>
            <td>
                <?php
                if(!empty($page->getChapitreId())): ?>
                    <a href="/chapitre?chapitre_id=<?= $page->getChapitreId()?>"><?= $page->getChapitre()->getTitre()?></a>
                <?php endif; ?>
            </td>
            <td><a href="/page/pages?auteur_id=<?= $page->getAuteurId()?>"><?= $page->getAuteur()->getFullName() ?></a></td>
            <td><?= date('d M, Y', strtotime($page->getDateCreation())) ?></td>
        </tr>
    <?php
        endforeach;
    ?>

    </tbody>
    <tfoot>
    <tr>
        <th>Catégorie</th>
        <th>Titre</th>
        <th>Description</th>
        <th>Personnage</th>
        <th>Chapitre</th>
        <th>Auteur</th>
        <th>Date de publication</th>
    </tr>
    </tfoot>
</table>
<?php
else:
        echo "Aucun résultat";
        endif;
        ?>


<script>
    $(document).ready(function () {
        $('#table_id').DataTable({
            pagingType: 'full_numbers',
        });
    });
</script>

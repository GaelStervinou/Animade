<main>
    <section id="dashboard">
        <div class="grid">
            <div class="row">
                <?php include('View/admin/sidebar.view.php'); ?>
                <div class="col-10">
                    <h1>ANIMADE ONE PIECE</h1>
                    <div class="row block-stat">
                        <div class="images-stat">
                            <img src="../assets/images/stat_1.png" alt="Statistics">
                            <img src="../assets/images/stat_1.png" alt="Statistics">
                        </div>
                    </div>
                    <div class="row block-stat ">
                        <div class="col-2 stat-nombre">
                            <p>Nombre d'articles publiés</p>
                            <h1><?= count($pages) ?></h1>
                        </div>
                        <div class="col-2 stat-nombre">
                            <p>Nombre d'utilisateurs inscrits</p>
                            <h1><?= count($users) ?></h1>
                        </div>
                        <div class="col-2 stat-nombre">
                            <p>Nombre de commentaires signalés</p>
                            <h1><?= count($signalements) ?></h1>
                        </div>
                    </div>
                    <div class="articles-dashboard">
                        <h1>Articles :</h1>
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
                                    <!-- TODO: rendre les infos cliquables ( on clique sur le nom du perso et on arrive sur sa fiche par ex)!-->
                                    <td><a href="/categorie?categorie_id=<?= $page->getCategorieId()?>"><?= $page->getCategorie()->getNom()?></a></td>
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
                                    <td><?= date('Y-m-d', strtotime($page->getDateCreation()))?></td>
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
                    </div>
                </div>
            </div>
        </div>
    </section>
</main>
<script>
    updateDataTable();
</script>
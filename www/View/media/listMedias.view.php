<main>
    <section id="login-form">
        <div class="grid">
            <div id="login-form">
                <div class="row">
                    <div class="table">
                        <table id="table_id" class="display">
                            <thead>
                            <tr>
                                <th>Image</th>
                                <th>Nom</th>
                                <th>Statut</th>
                                <th>Action</th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php foreach ($medias as $media): ?>
                                <tr>
                                    <td>
                                        <img src="../<?= $media->getChemin()?>" alt="Image du chapitre"
                                             width="300"
                                             height="300" >
                                    </td>
                                    <td><?= $media->getNom()?></td>
                                    <?php
                                        if($media->getStatut() === 2):
                                        ?>
                                            <td>Actif</td>
                                            <td><a href="/media/updateStatut?media_id=<?=$media->getId()?>">Supprimer</a></td>
<?php
                                        else:
                                        ?>
                                    <td>Supprimé</td>
                                    <td><a href="/media/updateStatut?media_id=<?=$media->getId()?>">Activer</a></td>
                                </tr>
                            <?php
                            endif;
                            endforeach; ?>

                            </tbody>
                            <tfoot>
                            <tr>
                                <th>Nom</th>
                                <th>Statut</th>
                                <th>Action</th>
                            </tr>
                            </tfoot>
                        </table>


                        <script>
                            updateDataTable();
                        </script>
                    </div>
                </div>
            </div>
        </div>
    </section>
</main>


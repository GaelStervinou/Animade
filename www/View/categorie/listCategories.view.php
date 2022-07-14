<?php include "View/head.view.php";?>
<main>
    <section id="login-form">
        <div class="grid">
            <div id="login-form">
                <div class="row">
                    <div class="table">
                        <table id="table_id" class="display" style="width: 1200px;">
                            <thead>
                                <tr>
                                    <th>Nom</th>
                                    <th>Description</th>
                                    <th>Parent</th>
                                    <th>Nombre d'articles</th>
                                </tr>
                            </thead>
                            <tbody>
                            <?php foreach ($categories as $categorie):?>
                                <tr style="text-align: center">
                                    <!-- TODO: rendre les infos cliquables ( on clique sur le nom du perso et on arrive sur sa fiche par ex)!-->
                                    <td><?= $categorie->getNom()?></td>
                                    <td><?= substr($categorie->getDescription(), 0, 40)?></td>
                                    <td>
                                        <?php
                                            if(!empty($categorie->getParentId())):
                                        ?>
                                            <a href="/categorie?categorie_id=<?= $categorie->getParentId()?>"><?= $categorie->getParent()->getNom()?></a>
                                        <?php
                                            else:
                                                echo 'Aucun';
                                            endif;
                                        ?>
                                    </td>
                                    <td>
                                        <?php
                                        if(!empty($categorie->getPages())): ?>
                                            <a href="/page/pages?categorie_id=<?= $categorie->getId()?>"><?= count($categorie->getPages())?></a>
                                        <?php
                                            else:
                                                echo "0";
                                        endif; ?>
                                    </td>

                                </tr>
                            <?php endforeach; ?>

                            </tbody>
                            <tfoot>
                            <tr>
                                <th>Nom</th>
                                <th>Description</th>
                                <th>Parent</th>
                                <th>Nombre d'articles</th>
                            </tr>
                            </tfoot>
                        </table>


                        <script>
                            $(document).ready(function () {
                                $('#table_id').DataTable({
                                    pagingType: 'full_numbers',
                                });
                            });
                        </script>
                    </div>
                </div>
            </div>  
        </div>
    </section>
</main>
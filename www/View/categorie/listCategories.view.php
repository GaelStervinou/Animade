<main>
    <section id="login-form">
        <div class="grid">
            <div id="login-form">
                <div class="row">
                    <div class="table">
                        <?php
                        if(\App\Core\Security::canAsAdmin()):
                        ?>
                            <a href="/categorie/new">Nouvelle catégorie</a>
                        <?php
                        endif;
                        ?>
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
                                    <td><a href="/categorie?categorie_id=<?=$categorie->getId()?>"><?= $categorie->getNom()?></a></td>
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
                            updateDataTable();
                        </script>
                    </div>
                </div>
            </div>  
        </div>
    </section>
</main>
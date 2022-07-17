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
                                <th>Articles</th>
                                <th>Nombre d'articles</th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php
                            foreach ($auteurs as $auteur):?>
                                <tr style="text-align: center">
                                    <td><?= $auteur->getFullName()?></td>
                                        <?php
                                        if(!empty($auteur->getPages())): ?>
                                    <td>
                                        <a href="/page/pages?auteur_id=<?=$auteur->getId()?>">Articles</a>
                                    </td>
                                        <td>
                                            <?= count($auteur->getPages())?>
                                        </td>
                                        <?php
                                        else:?>
                                        <td>Aucun</td>
                                        <td>0</td>
                                    <?php
                                        endif; ?>

                                </tr>
                            <?php endforeach; ?>

                            </tbody>
                            <tfoot>
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
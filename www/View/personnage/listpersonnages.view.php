<main>
    <section id="login-form">
        <div class="grid">
            <div id="login-form">
                <div class="row">
                    <div class="table">
                        <?php
                        if(\App\Core\Security::canAsAdmin()):
                            ?>
                            <a href="/personnage/new">Nouveau personnage</a>
                        <?php
                        endif;
                        ?>
                        <table id="table_id" class="display" style="width: 1200px;">
                            <thead>
                            <tr>
                                <th>Nom</th>
                                <th>Nombre d'articles</th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php
                            foreach ($personnages as $personnage):?>
                                <tr style="text-align: center">
                                    <td><a href="/personnage?personnage_id=<?=$personnage->getId()?>"><?= $personnage->getNom()?></a></td>
                                    <td>
                                        <?php
                                        if(!empty($personnage->getPages())): ?>
                                            <a href="/page/pages?personnage_id=<?=$personnage->getId()?>"><?= count($personnage->getPages())?></a>
                                        <?php
                                        else:
                                            echo "0";
                                        endif; ?>
                                    </td>

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
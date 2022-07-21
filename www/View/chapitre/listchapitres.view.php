<main>
    <section id="login-form">
        <div class="grid">
            <div id="login-form">
                <div class="row">
                    <div class="table">
                        <?php
                        if(\App\Core\Security::canAsAdmin()):
                            ?>
                            <a href="/chapitre/new">Nouveau chapitre</a>
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
                            foreach ($chapitres as $chapitre):?>
                                <tr style="text-align: center">
                                    <td><a href="/chapitre?chapitre_id=<?=$chapitre->getId()?>"><?= $chapitre->getTitre()?></a></td>
                                    <td>
                                        <?php
                                        if(!empty($chapitre->getPages())): ?>
                                            <a href="/page/pages?chapitre_id=<?=$chapitre->getId()?>"><?= count($chapitre->getPages())?></a>
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
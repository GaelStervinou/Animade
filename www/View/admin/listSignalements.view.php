<main>
    <section id="login-form">
        <div class="grid">
            <div id="login-form">
                <div class="row">
                    <div class="table">
                        <table id="table_id" class="display">
                        <thead>
                        <tr>
                            <th>Commentaire</th>
                            <th>Signalé par</th>
                            <th>Dernier signalement</th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php foreach ($signalements as $signalement): ?>
                            <tr>
                                <td>
                                    <a href="/commentaire?commentaire_id=<?= $signalement->getCommentaireId()?>">Voir le commentaire</a>
                                    <?= substr($signalement->getCommentaire()->getContenu(), 0, 50)?>
                                </td>
                                <td><?= $signalement->getUser()->getFullName()?></td>
                                <td><?= date('d M, Y', strtotime($signalement->getDateCreation())) ?></td>
                            </tr>
                        <?php endforeach; ?>

                        </tbody>
                        <tfoot>
                        <tr>
                            <th>Commentaire</th>
                            <th>Signalé par</th>
                            <th>Dernier signalement</th>
                        </tr>
                        </tfoot>
                        </table>


                    <script>
                        $('#table_id').DataTable({
                            pagingType: 'full_numbers',
                        });
                    </script>
                    </div>      
                </div>
            </div>
        </div>
    </section>
</main>


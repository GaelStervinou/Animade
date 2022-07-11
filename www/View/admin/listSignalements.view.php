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
            <td><?= $signalement->getDateCreation() ?></td>
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
    $(document).ready(function () {
        $('#table_id').DataTable({
            pagingType: 'full_numbers',
        });
    });
</script>

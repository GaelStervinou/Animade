<h1>
    Résultats
</h1>

<?php
// TODO faire un truc pcq resultats n'est jamais vide
    if(empty($resultats)):
?>
    <p>
        Aucun résultat pour la recherche <?= $recherche?>
    </p>

<?php
    else:
        ?>
        <table id="table_id" class="display" style="width: 1200px;">
        <thead>
        <tr>
            <th>Type</th>
            <th>Nom</th>
            <th>Description</th>
        </tr>
        </thead>
        <tbody>
        <?php
        foreach($resultats as $resultat => $resultatList):
            if(!empty($resultatList)):
                ?>

                    <?php
                    foreach ($resultatList as $res):
                        ?>
                        <tr style="text-align: center">
                            <td>
                                <?= substr($resultat, 0, strlen($resultat)-1) ?>
                            </td>
                            <?php
                                switch ($resultat){
                                    case 'pages':
                                        echo '<td><a href="/page?page='.$res->getSlug().'">'.$res->getTitre().'</a></td>';
                                        break;
                                    case 'auteurs':
                                        echo '<td><a href="/page/pages?auteur_id='.$res->getId().'">'.$res->getFullName().'</a></td>';
                                        break;
                                    case 'categories':
                                        echo '<td><a href="/page/pages?categorie_id='.$res->getId().'">'.$res->getNom().'</a></td>';
                                        break;
                                    case 'personnages':
                                        echo '<td><a href="/page/pages?personnage_id='.$res->getId().'">'.$res->getNom().'</a></td>';
                                        break;
                                }
                                if(method_exists($res, "getDescription")){
                                    echo '<td>'.substr($res->getDescription(), 0, 40).'</td>';
                                }
                                else{
                                    echo '<td>Aucune description</td>';
                                }
                            ?>

                        </tr>
                    <?php
                    endforeach;
            endif;

            ?>



<?php
    endforeach;
    endif;
    ?>
        </tbody>
            <tfoot>
            <tr>
                <th>Type</th>
                <th>Nom</th>
                <th>Description</th>
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
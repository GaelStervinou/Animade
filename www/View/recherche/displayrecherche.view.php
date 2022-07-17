<main>
    <section id="login-form">
        <div class="grid">
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
                <table id="table_id" class="display" style="width: 100%;">
                <thead>
                <tr>
                    <th>Type</th>
                    <th>Nom</th>
                    <th>Description</th>
                    <th>Date de publication</th>
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
                                            case 'chapitres':
                                                echo '<td><a href="/page/pages?chapitre_id='.$res->getId().'">'.$res->getTitre().'</a></td>';
                                                break;
                                        }
                                        if(method_exists($res, "getDescription")){
                                            echo '<td>'.substr($res->getDescription(), 0, 40).'</td>';
                                        }
                                        else{
                                            echo '<td>Aucune description</td>';
                                        }
                                    if(method_exists($res, "getDateCreation")){
                                        echo '<td>'.$res->getDateCreation().'</td>';
                                    }
                                    else{
                                        echo '<td>Aucune</td>';
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
                        <th>Date de publication</th>
                    </tr>
                    </tfoot>
                </table>
        </div>
    </section>
</main>

<script>
    updateDataTable();
</script>
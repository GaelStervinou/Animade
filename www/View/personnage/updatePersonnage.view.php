<?php include "View/head.view.php";?>
<main>
    <section id="login-form">
        <div class="grid">
            <div id="login-form">
                <img src="../<?= $personnage->getMedia()->getChemin()?>" alt="Image de l'article"
                     width="300"
                     height="300" >
                <div class="row">
                    <div class="col-3">
                        <?php
                            $this->includePartial('form', $personnage->getFormUpdatePersonnage());
                        ?>

                    </div>
                </div>

            </div>
        </div>
    </section>
</main>
</body>

</html>
<main>
    <section id="login-form">
        <div class="grid">
            <div id="login-form">
                <?php
                if($personnage->hasMedia() === true):
                ?>
                <img src="../<?= $personnage->getMedia()->getChemin()?>" alt="Image du personnage"
                     width="300"
                     height="300" >
                <?php
                endif;
                ?>
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
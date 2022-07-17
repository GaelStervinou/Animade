<main>
    <section id="login-form">
        <div class="grid">
            <?php
            if($user->hasMedia() === true):
            ?>

            <img src="../<?= $user->getMedia()->getChemin()?>" alt="Image du chapitre"
                 width="300"
                 height="300" >
            <?php
endif;
?>
            <div id="login-form">
                <div class="row">
                    <div class="col-4">

                    <?php
                        $this->includePartial('form', $user->getFormUpdate($user->getId())); ?>

                    </div>

                </div>
            </div>

        </div>
    </section>
</main>

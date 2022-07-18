<main>
    <section id="login-form">
        <div class="grid">
            <?php
            if($userUpdate->hasMedia() === true):
            ?>

            <img src="../<?= $userUpdate->getMedia()->getChemin()?>" alt="Image du chapitre"
                 width="300"
                 height="300" >
            <?php
endif;
?>
            <div id="login-form">
                <div class="row">
                    <div class="col-4">

                    <?php
                        $this->includePartial('form', $userUpdate->getFormUpdate($userUpdate->getId())); ?>

                    </div>

                </div>
            </div>

        </div>
    </section>
</main>

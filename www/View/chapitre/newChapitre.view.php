<?php include "View/head.view.php";?>
    <main>
        <section id="login-form">
            <div class="grid">
                <div id="login-form">
                    <div class="row">
                        <div class="col-3">
                            <?php $this->includePartial('form', $chapitre->getNewChapitreForm());
                            ?>

                        </div>

                    </div>
                </div>

            </div>
        </section>
    </main>
</body>

</html>
<main>
    <section id="login-form">
        <div class="grid">
            <div id="login-form">
                <div class="row">
                    <div class="col-3">
                        <?php
                            $this->includePartial('form', $categorie->getFormUpdateCategorie());
                        ?>
                    </div>
                </div>
            </div>
        </div>
    </section>
</main>
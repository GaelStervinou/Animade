<main>
    <section id="login-form">
        <div class="grid">
            <div id="login-form">
                <div class="row">
                    <div class="col-3">

                        <?php
                        $this->includePartial('form', $user->getFormLogin());
                        ?>
                    </div>

                </div>
            </div>

        </div>
    </section>
</main>
<main>

    <section id="login-form">
        <div class="grid">
            <div id="login-form">
                <div class="row">
                    <div class="col-4">

                        <?php
                        $this->includePartial('form', $user->getUpdatePasswordForm()); ?>

                    </div>

                </div>
            </div>

        </div>
    </section>

</main>

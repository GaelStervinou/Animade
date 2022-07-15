<main>
    <?php
    if(!empty($message)):
    ?>
    <div class="message">
        <?= $message?>
    <?php
    else:
    ?>
    <section id="login-form">
        <div class="grid">
            <div id="login-form">
                <div class="row">
                    <div class="col-4">

                        <?php
                        $this->includePartial('form', $user->getEmailPasswordForgottenForm()); ?>

                    </div>

                </div>
            </div>

        </div>
    </section>
    <?php
    endif;
    ?>
</main>
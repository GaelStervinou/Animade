<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <title>Dashboard</title>
    <link rel="stylesheet" type="text/css" href="dist/main.css">
</head>
<body>


<main>
    <section id="login-form">
        <div class="grid">
            <div id="login-form">
                <div class="row">
                    <div class="col-3">

                        <?php
                        $this->includePartial('form', $user->getFormLogin()); ?>
                    </div>

                </div>
            </div>

        </div>
    </section>
</main>


</body>
</html>


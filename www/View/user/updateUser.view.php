<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <title>Dashboard</title>
    <link rel="stylesheet" type="text/css" href="dist/main.css">
</head>
<body>
<header id="site-header">
    <div class="container">
        <a href="#">
            <img src="../assets/images/logo_animade.jpg" alt="Logo Animade">
        </a>
        <button id="menu-button"></button>
        <nav id="site-nav">
            <ul>
                <li><a href="#">Accueil</a></li>
                <li><a href="#">Mon Site</a></li>
                <li><a href="#">F.A.Q</a></li>
            </ul>
        </nav>
    </div>
</header>

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
</body>
</html>
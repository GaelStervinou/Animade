<!DOCTYPE html>
<html lang="fr">
<head>
    <meta http-equiv="Content-type" content="text/html;charset=utf-8" />
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
            <div id="login-form">
                <div class="row">
                    <div class="col-4">

                    <?php $this->includePartial('form', $user->getFormRegister()); ?>
                        
                        <!-- <form method="POST" action="/login">
                            <h1>Se connecter</h1>
                            <div class="row">
                                <div class="col-6">
                                    <label for="identifiant">Email ou Pseudo :</label>
                                </div>
                            </div>
                            <div class="row field">
                                <div class="col-6">
                                    <input type="text" id="identifiant">
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-6">
                                    <label for="password">Mot de passe :</label>
                                </div>
                            </div>
                            <div class="row field">
                                <div class="col-6">
                                    <input type="test" id="password">
                                </div>
                            </div>
                            <div class="row">
                                <button class="button" type="submit">Connexion</button>

                            </div>
                        </form> -->
                    </div>

                </div>
            </div>

        </div>
    </section>
</main>
</body>
</html>


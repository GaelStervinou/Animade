<!DOCTYPE html>
<html lang="fr">
<?php
use App\Core\Security;
$user = Security::getUser();?>
<head>
    <meta charset="UTF-8">
    <title><?=$meta['titre'] ?? 'Template de back'?></title>
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.12.1/css/jquery.dataTables.css">
    <script
            src="https://code.jquery.com/jquery-3.6.0.min.js"
            integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4="
            crossorigin="anonymous"></script>
    <script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/1.12.1/js/jquery.dataTables.js"></script>
    <meta name="description" content="ceci est la description de ma page">
    <?php
    if(isset($meta)):
        foreach($meta['script'] as $script):
            ?>
            <script type="text/javascript" charset="utf8" src="<?= $script?>"></script>
        <?php endforeach; endif; ?>
    <script type="text/javascript" charset="utf8" src="../dist/js/loadCss.js"></script>

</head>
<body>
    <header>
        <div class="container">
            <?php
            $ua = strtolower($_SERVER["HTTP_USER_AGENT"]);
            $isMob = is_numeric(strpos($ua, "mobile"));
            if($isMob):
                ?>
                <div class="mobile_access">
                    <p>Nous vous recommandons d'accéder au site via un ordinateur pour une meilleure expérience.</p>
                </div>
            <?php
            endif;
            ?>
            <a href="#">
                <img src="../assets/images/logo_animade.jpg" alt="Logo Animade">
            </a>
            <button id="menu-button"></button>
            <?php
            if($user !== false):
            ?>
            <nav id="site-nav">
                <ul>
                    <li><a href="/">Accueil</a></li>
                    <li><a href="/admin/dashboard">Dashboard</a></li>
                    <?php
                    if(Security::canAsSuperAdmin()):
                    ?>
                    <li><a href="/admin/administration">Paramètres</a></li>
                        <?php
                    if(DBLINK !== null):
                        ?>
                    <li><a href="<?=DBLINK?>">Base de données</a></li>
                    <?php
                    endif;
                    endif;
                    ?>
                    <li><a href="#">Nous contacter</a></li>
                </ul>
            </nav>
            <?php
            endif;
            ?>
        </div>

        <div id="recherche" class="recherche">
            <form action="/recherche" method="GET">
                <input name="recherche" class="class_test" type="text" placeholder="Rechercher">

                <input id="submit" type="submit" value="Search">
            </form>
        </div>
    </header>

<?php
include $this->view.".view.php";
include('View/footer.view.php');
?>

</body>
</html>
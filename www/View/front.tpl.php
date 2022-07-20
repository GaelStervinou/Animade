<!DOCTYPE html>
<?php

use App\Helpers\UrlHelper;

use App\Core\Security;

if(!isset($currentUser)){
    $currentUser = Security::getUser();
}
if(!UrlHelper::isAjaxRequest()):
?>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title><?=$meta['titre'] ?? SITENAME?></title>
    <?php
    if(defined('FAVICON')):
    ?>
    <link rel="icon" type="image/x-icon" href="../assets/images/administration/<?=FAVICON?>">
    <?php
    endif;
    ?>
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.12.1/css/jquery.dataTables.css">
    <script
            src="https://code.jquery.com/jquery-3.6.0.min.js"
            integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4="
            crossorigin="anonymous"></script>
    <script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/1.12.1/js/jquery.dataTables.min.js"></script>
    <?php
    if(isset($meta['link'])){
        echo "<link rel='stylesheet' type='text/css' href='".$meta['link']."'>";
    }
    if(isset($meta['script'])):
        foreach($meta['script'] as $script):
    ?>

            <script type="text/javascript" charset="utf8" src="<?= $script?>"></script>
    <?php endforeach; endif; ?>
    <script type="text/javascript" charset="utf8" src="../dist/js/loadcss.js"></script>

    <meta name="description" content="ceci est la description de ma page">
</head>
<body>

    <header id="site-header">
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
            if(defined('LOGO')):
                ?>
        <a href="/">
            <img src="../assets/images/administration/<?=LOGO?>" alt="Logo du site" style="width: 100px;">
        </a>
        <?php
        endif;
        ?>
            <button id="menu-button"></button>
            <nav id="site-nav">
                <ul>
                    <li><a href="/">Accueil</a></li>
                    <li><a href="/contact">Nous contacter</a></li>
                    <?php
                    if($currentUser !== false):
                        if($currentUser->getRoleId() === 2):
                            ?>
                            <li><a href="/page/new">Nouvelle page</a></li>
                        <?php
                        endif;

                    ?>
                        <li><a href="/media/listMedias">Médias</a></li>
                        <li><a href="/user?user_id=<?=$currentUser->getId()?>">Profil</a></li>
                        <li><a href="/logout">Se déconnecter</a></li>
                    <?php
                    endif;
                    ?>
                </ul>
            </nav>
        </div>
        <?php
        if($currentUser !== false):
            ?>
            <div id="recherche" class="recherche">
                <form action="/recherche" method="GET">
                    <input name="recherche" class="class_test" type="text" placeholder="Rechercher">

                    <input id="submit" type="submit" value="Search">
                </form>
            </div>
        <?php
        endif;
        ?>
    </header>

<?php
endif;
include $this->view.".view.php";
include('View/footer.view.php');
?>

</body>
</html>
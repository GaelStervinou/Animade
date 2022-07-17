<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title><?=$meta['titre'] ?? ''?></title>
    <?php
    if(defined('FAVICON')):
        ?>
        <link rel="icon" type="image/x-icon" href="../assets/images/<?=FAVICON?>">
    <?php
    endif;
    ?>
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.12.1/css/jquery.dataTables.css">
    <script
        src="https://code.jquery.com/jquery-3.6.0.min.js"
        integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4="
        crossorigin="anonymous"></script>
    <script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/1.12.1/js/jquery.dataTables.js"></script>
    <meta name="description" content="ceci est la description de ma page">
    <?php
    if(isset($meta['script'])):
        foreach($meta['script'] as $script):
            ?>
            <script type="text/javascript" charset="utf8" src="<?= $script?>"></script>
        <?php endforeach; endif; ?>
    <script type="text/javascript" charset="utf8" src="../dist/js/loadcss.js"></script>

</head>
<body>
<?php
include $this->view.".view.php";?>
</body>
</html>



<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Template de back</title>
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.12.1/css/jquery.dataTables.css">
    <script
            src="https://code.jquery.com/jquery-3.6.0.min.js"
            integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4="
            crossorigin="anonymous"></script>
    <script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/1.12.1/js/jquery.dataTables.js"></script>
    <meta name="description" content="ceci est la description de ma page">
    <script type="text/javascript" charset="utf8" src="../dist/js/loadCss.js"></script>
</head>
<body>
<main>
    <section id="login-form">
        <div class="grid">
            <div id="login-form">
                <div class="row">
                    <div class="col-3">

                        <?php
                        $this->includePartial('form', $installForm);
                        ?>
                    </div>
                </div>
            </div>

        </div>
    </section>
</main>
</body>
</html>

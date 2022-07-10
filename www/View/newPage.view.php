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
            <div id="login-form">
                <div class="row">
                    <div class="col-3">
                        <?php //$this->includePartial('form', $page->getFormNewPage());
                        //TODO insérer les select de chapitre?>
                        <script src="https://cdn.ckeditor.com/ckeditor5/34.1.0/decoupled-document/ckeditor.js"></script>
                        <div id="toolbar-container" style="width:500px;"></div>

                        <!-- This container will become the editable. -->
                        <div id="editor" style="width:500px;">
                            <p>This is the initial editor content.</p>
                        </div>

                        <script>
                            DecoupledEditor
                                .create( document.querySelector( '#editor' ) )
                                .then( editor => {
                                    const toolbarContainer = document.querySelector( '#toolbar-container' );

                                    toolbarContainer.appendChild( editor.ui.view.toolbar.element );
                                } )
                                .catch( error => {
                                    console.error( error );
                                } );
                        </script>
                    </div>

                </div>
            </div>

        </div>
    </section>
</main>
</body>
</html>

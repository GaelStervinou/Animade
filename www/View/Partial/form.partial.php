<link rel="stylesheet" type="text/css" href="dist/main.css">
<form method="<?= $config['config']['method'] ?>" action="<?= $config['config']['action'] ?>" enctype="multipart/form-data">


<h1><?= $config['config']['title']?></h1>

    <?php foreach ($config['inputs'] as $name => $input):
    if(!empty($input['label'])):
        ?>
        
        <div class="row">
            <div class="col-6">
                <label for="<?= $input['id']?>"><?= $input['label']?></label>
            </div>
        </div>
        
    <?php endif;
    switch($input['type']):
        case 'select' :
?>
<!-- Input Email -->
    <div class="row field">
        <div class="col-6">
            <input 
                name="<?= $name?>" 
                id="<?= $input['id']?>" 
                class="<?= $input['class']?>"
                placeholder="<?= $input['placeholder']?>" 
                <?= empty($input['required'])?:'required=required'?>
            >
        </div>
    </div>

    <?php break;
        case 'wysiwyg':
?>
        <div id="editor"></div>
        <script src="https://cdn.ckeditor.com/ckeditor5/34.1.0/classic/ckeditor.js"></script>
        <script>
            ClassicEditor
                .create( document.querySelector( '#editor' ) )
                .catch( error => {
                    console.error( error );
                } );
        </script>

            <?php break;
    default : ?>

   <!-- Input MDP -->
   <div class="row field">
        <div class="col-6">
            <input 
            name="<?= $name?>"
            id="<?= $input['id']?>"
            type="<?= $input['type']?>"
            class="<?= $input['class']?>"
            placeholder="<?= $input['placeholder']?>"
            <?= empty($input['required'])?:'required=required'?>
            >
            </input>
        </div>
    </div>
  

    <?php endswitch; endforeach; ?>
    <div class="row">
        <button  class="button" type="submit" value="<?= $config['config']['submit']?>">Connexion
    </div>
    
</form>
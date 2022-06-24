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
            </input>
        </div>
    </div>

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
        <button  class="button" type="submit" value="<?= $config['config']['submit']?>"><?= $config['config']['submit']?>
    </div>

        <div class="row">
            <a href="password_forgotten">Mot de passe oublié</a>
        </div>
        

    
    
</form>
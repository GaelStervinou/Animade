<?php
$csrfToken = md5(uniqid(mt_rand(), true));
$_SESSION['csrfToken'] = $csrfToken;
?>
<form id="form" method="<?= $config['config']['method'] ?>" action="<?= $config['config']['action'] ?>" enctype="multipart/form-data">

    <h1><?= $config['config']['title']?></h1>

    <?php
    $wysiwyg = 0;
    foreach ($config['inputs'] as $name => $input):
    if(!isset($input['authorized'])){
        $input['authorized'] = true;
    }
    if($input['authorized'] == true):
    if(!empty($input['label'])):
        ?>

        <div class="row">
            <div class="col-6">
                <label for="<?= $input['id']?>"><?= $input['label']?></label>
            </div>
        </div>

    <?php endif;


    switch($input['type']):
    case 'a':
    ?>
    <div class="row field">
        <div class="col-6">
            <a href="<?= $input['href']?>"><?= $input['placeholder']?></a>
        </div>
        <?php
        break;
        case 'select' :

            ?>

            <div class="row field">
                <div class="col-6">
                    <select
                        name="<?= $name?>"
                        id="<?= $input['id']?>"
                        class="<?= $input['class']?>"
                        <?php
                        if(!empty($input['required'])):
                        ?>
                        required='required'
                    <?php endif;?>
                    <option></option>
                    <?php

                    foreach ($input['options'] as $dico => $value):

                        if($input['default_value'] == $value):
                            $selected = " selected=\"selected\"";
                        else:
                            $selected = '';
                        endif;
                        echo "<option value=\"{$value}\" " .$selected. "> {$dico}</option>"
                        ?>

                    <?php endforeach; ?>
                    </select>
                </div>
            </div>

            <?php break;
        case 'wysiwyg':
            $wysiwyg = 1;
            ?>
            <script src="https://cdn.ckeditor.com/ckeditor5/34.1.0/classic/ckeditor.js"></script>

            <div id="toolbar-container" style="width:500px;"></div>

            <textarea id="editor" name="<?= $name?>" style="width:500px;" rows="15" cols="33"><?= $input['default_value']?></textarea>

            <?php
            break;
        case 'textarea': ?>
            <div class="row field">
                <div class="col-12">
                    <textarea name="<?= $name?>"
                              id="<?= $input['id']?>"
                              rows="<?= $input['rows']?>"
                              cols="<?= $input['cols']?>"
                              class="<?= $input['class']?>"
                              placeholder="<?= $input['placeholder']?>"
                     <?php
                     if(!empty($input['required'])):
                         ?>
                         required='required'
                     <?php endif;?>
                    ><?php if(!empty($input['default_value'])):?><?=$input['default_value']?>
                        <?php endif;?></textarea>
                </div>
            </div>

            <?php break;
        case 'file':?>
            <div class="row field">
                <div class="col-6">
                    <input
                        name="<?= $name?>"
                        id="<?= $input['id']?>"
                        type="<?= $input['type']?>"
                        class="<?= $input['class']?>"
                        accept="image/jpeg, image/jpg, image/png"
                        <?php
                        if(!empty($input['required'])):
                            ?>
                            required='required'
                        <?php endif;?>
                    />
                </div>
            </div>
            <?php break;
        default :?>
            <div class="row field">
                <div class="col-6">
                    <input
                        name="<?= $name?>"
                        id="<?= $input['id']?>"
                        type="<?= $input['type']?>"
                        class="<?= $input['class']?>"
                        placeholder="<?= $input['placeholder']?>"
                        <?php
                        if(!empty($input['required'])):
                            ?>
                            required='required'
                        <?php endif;?>
                        <?php
                        if(!empty($input['default_value'])):
                            ?>
                            value="<?= $input['default_value']?>"
                        <?php endif;?>
                    />
                </div>
            </div>


        <?php endswitch; endif; endforeach; ?>
        <input type="hidden" name="csrfToken" value="<?= $csrfToken ?>" />
        <div class="row">
            <button id="submit" class="button" type="submit" value="<?= $config['config']['submit']?>"><?= $config['config']['submit']?>
        </div>

        <?php if($wysiwyg == 1): ?>
            <script>
                ClassicEditor
                    .create( document.querySelector( '#editor' ) )
                    .then(editor =>{
                    })
                    .catch( error => {
                        console.error( error );
                    } );
            </script>
        <?php endif;?>
</form>
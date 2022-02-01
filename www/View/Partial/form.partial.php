Création du formulaire

<form method="<?= $config['config']['method'] ?>" action="<?= $config['config']['action'] ?>" enctype="multipart/form-data">

    <?php foreach ($config['inputs'] as $name => $input):
        switch($input['type']):
            case 'select' :
    ?>
                <select name="<?= $name?>" id="<?= $input['id']?>" class="<?= $input['class']?>"
                        placeholder="<?= $input['placeholder']?>" <?= empty($input['required'])?:'required=required'?>>
                    <?php foreach($input['options'] as $label => $info):?>
                        <option value="<?=$info['value']?>" <?= empty($info['selected'])?:'selected=selected'?>><?=$label?></option>
                    <?php endforeach;?>
                </select>
                <br>
                <br>
            <?php break;
            case 'textarea' : ?>
            <textarea name="<?= $name?>"
                      id="<?= $input['id']?>"
                      class="<?= $input['class']?>"
                      placeholder="<?= $input['placeholder']?>"
                      cols="<?= $input['cols']?>" rows="<?= $input['rows']?>"
                <?= empty($input['required'])?:'required=required'?>>
            </textarea>
                <br>
                <br>
                <?php break;
            case 'radiobutton' :
                foreach($input['value'] as $value => $info ):?>
                    <input name="<?= $name?>"
                           id="<?= $input['id']?>"
                           type="radio"
                           class="<?= $input['class']?>"
                           value="<?= $value?>"
                           placeholder="<?= $input['placeholder']?>"
                        <?= empty($info['checked'])?:'checked'?>
                        <?= empty($input['required'])?:'required=required'?>
                    >
                    <label for="<?= $name?>"><?= $info['label']?></label>
                <?php endforeach; ?>
                    <br>
                    <br>

                <?php break;
            case 'checkbox' : ?>
                <input name="<?= $name?>"
                       id="<?= $input['id']?>"
                       type="<?= $input['type']?>"
                       class="<?= $input['class']?>"
                       value="<?= $input['value']['value']?>"
                    <?= empty($input['value']['checked'])?:'checked'?>
                >
                <label for="<?= $name?>"><?= $input['label']?></label>
                <br>
                <br>
                <?php break;
            case 'file' : ?>
                <input name="<?= $name?>"
                       id="<?= $input['id']?>"
                       type="<?= $input['type']?>"
                       class="<?= $input['class']?>"
                       accept="image/*,.pdf,.doc,.docx"
                >
                <label for="<?= $name?>"><?= $input['label']?></label>
                <br>
                <br>
            <?php break;
            default : ?>

        <input name="<?= $name?>"
               id="<?= $input['id']?>"
               type="<?= $input['type']?>"
               class="<?= $input['class']?>"
               placeholder="<?= $input['placeholder']?>"
               <?= empty($input['required'])?:'required=required'?>
        >
    <br>
    <br>

    <?php endswitch; endforeach; ?>
    <input type="submit" value="<?= $config['config']['submit']?>">
</form>
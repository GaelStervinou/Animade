<?php
namespace App\Core;

use App\Model\Commentaire as CommentaireModel;
use App\Model\Signalement;

class Validator{
    // modifier les messages d'erreurs par la clé error renseigné dans config
    public static function run($config, $data, $hiddenFields=0): array
    {
        $result = [];
        $inputs_nb = self::countAuthorizedFields($config['inputs'], $hiddenFields);

        if(count($data) != $inputs_nb){
            $result[] = "Formulaire modifié pas l'utilistaeur";
        }
        foreach($config['inputs'] as $name => $input){

            if(!isset($input['authorized'])){
                $input['authorized'] = true;
            }
            if($input['authorized'] == true){
                if(!isset($data[$name]) && $input['required'] === true){
                    $result[] = "Il manque des champs";
                }
                if(!empty($input['required']) && empty($data[$name])){
                    $result[] = "Vous devez remplir le champs ". $name;
                }
                if($input['type'] == 'password' && !self::checkPassword($data[$name])){
                    $result[] = "Mot de passe incorrect";
                }
                if($input['type'] == 'email' && !self::checkEmail($data[$name])){
                    $result[] = "Email incorrect";
                }
                if($input['type'] == 'select' && !empty($data[$name]) && !in_array($data[$name], array_values($input['options']))){
                    $result[] = "Valeur incorrecte pour le champs " . $name;
                }
            }
        }
        return $result;
    }

    public static function checkPassword($pwd): bool
    {
        return strlen($pwd) >=8 || $pwd <= 16
            && preg_match("/[a-z]/i", $pwd, $result)
            && preg_match("/[0-9]/", $pwd, $result);
    }

    public static function checkEmail($email): bool
    {
        return filter_var($email, FILTER_VALIDATE_EMAIL);
    }

    public static function sanitizeWysiwyg($content)
    {
        return trim($content);
    }
    public static function sanitizeSlug($slug)
    {
        return str_replace(" ", "_",trim($slug));
    }

    public static function countAuthorizedFields($inputs, $hiddenFields=0): int
    {
        $inputs_nb = 0;
        foreach($inputs as $name => $input){
            if(!isset($input['authorized'])){
                $input['authorized'] = true;
            }
            if($input['authorized'] === true){
                $inputs_nb += 1;
            }
        }
        return $inputs_nb+$hiddenFields;
    }

    public static function checkIfCommentExists($commentaire_id)
    {
        $commentaire = new CommentaireModel();
        if($commentaire->findOneBy($commentaire->getTable(), ['id' => $commentaire_id])->getAuteurId() ===
            Security::getUser()->getId()){
            return "Vous ne pouvez pas signaler votre commentaire";
        }elseif($commentaire->findOneBy($commentaire->getTable(), ['id' => $commentaire_id])){
            return true;
        }else{
            return "Commentaire introuvable";
        }

    }

    public static function checkIfNotAlreadySignaled($commentaire_id)
    {
        $signalement = new Signalement();
        if($signalement->findOneBy($signalement->getTable(), ['commentaire_id' => $commentaire_id, 'user_id' => Security::getUser()->getid()])) {
            return "Commentaire déjà signalé";
        }

        return true;
    }

    public static function canSignalComment($commentaire_id)
    {
        if(self::checkIfCommentExists($commentaire_id) === true
            && self::checkIfNotAlreadySignaled($commentaire_id) === true){
            return true;
        }
        return 'error';
    }
}
?>
<?php
namespace App\Core;

class Validator{
    // modifier les messages d'erreurs par la clé error renseigné dans config
    public static function run($config, $data): array
    {
        $result = [];
        $inputs_nb = self::countAuthorizedFields($config['inputs']);

        if(count($data) != $inputs_nb){
            $result[] = "Formulaire modifié pas l'utilistaeur";
        }
        foreach($config['inputs'] as $name => $input){

            if(!isset($input['authorized'])){
                $input['authorized'] = true;
            }
            if($input['authorized'] == true){
                if(!isset($data[$name]) && $input['required'] == true){
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

    public static function countAuthorizedFields($inputs): int
    {
        $inputs_nb = 0;
        foreach($inputs as $name => $input){
            if(!isset($input['authorized'])){
                $input['authorized'] = true;
            }
            if($input['authorized'] == true){
                $inputs_nb += 1;
            }
        }
        return $inputs_nb;
    }
}
?>
<?php
namespace App\Core;

class Validator{
    // modifier les messages d'erreurs par la clé error renseigné dans config
    public static function run($config, $data): array
    {
        $result = [];
        if(count($data) != count($config['inputs'])){
            $result[] = "Formulaire modifié pas l'utilistaeur";
        }
        foreach($config['inputs'] as $name => $input){

            if(!isset($data[$name]) && (!in_array($input['type'], ['checkbox', 'radiobutton']) && $input['required'] == true)){
                $result[] = "Il manque des champs";
            }
            if(!empty($input['required']) && empty($data[$name]) && $input['type'] == 'file'){
                $result[] = "Vous devez remplir le champs ". $name;
            }
            if($input['type'] == 'password' && !self::checkPassword($data[$name])){
                $result[] = "Mot de passe incorrect";
            }
            if($input['type'] == 'email' && !self::checkEmail($data[$name])){
                $result[] = "Email incorrect";;
            }
            if($input['type'] == 'file'){
                if($data[$name]['size'] > 4000000){
                    $result[] = "Fichier trop lourd";
                }
                if(strlen($data[$name]['name']) > 60){
                    $result[] = "Nom du fichier trop long";
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
}
?>
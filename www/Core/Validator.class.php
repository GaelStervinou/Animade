<?php
namespace App\Core;

use App\Model\Commentaire as CommentaireModel;
use App\Model\Signalement;

class Validator{
    // TODO modifier les messages d'erreurs par la clé error renseignée dans config
    /**
     * @param array $config
     * @param array $data
     * @param int $hiddenFields
     * @return array
     */
    public static function run(array $config, array $data, int $hiddenFields=0): array
    {
        $result = [];
        $inputs_nb = self::countAuthorizedFields($config['inputs'], $hiddenFields);

        if(count($data) !== $inputs_nb){
            $result[] = "Formulaire modifié pas l'utilistaeur";
        }
        foreach($config['inputs'] as $name => $input){
            if(!empty($data[$name]) && !in_array($input['type'], ["wysiwyg", "file"])){
                if($data[$name] !== strip_tags($data[$name])){
                    $result[] = "Impossible d'insérer des balises HTML dans le champ {$name}";
                }
            }
            if(!isset($input['authorized'])){
                $input['authorized'] = true;
            }
            if($input['authorized'] === true){
                if(!isset($data[$name]) && $input['required'] === true){
                    $result[] = "Il manque des champs";
                }
                if(!empty($input['required']) && empty($data[$name])){
                    $result[] = "Vous devez remplir le champs ". $name;
                }
                if($input['type'] === 'password' && !empty($data[$name]) && !self::checkPassword($data[$name])){
                    $result[] = "Mot de passe incorrect";
                }
                if($input['type'] === 'email' && !self::checkEmail($data[$name])){
                    $result[] = "Email incorrect";
                }
                if($input['type'] === 'select' && !empty($data[$name]) && !in_array($data[ $name ], array_values($input[ 'options' ]), false)){
                    Security::returnError(403, "Option non autorisée pour le champs ".$name);
                }
            }
        }
        return $result;
    }

    public static function checkPassword($pwd): bool
    {
        return strlen($pwd) >=8 || ($pwd <= 16
                //&& preg_match("/[a-z]/i", $pwd, $result)
                && preg_match("^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{8,}$", $pwd, $result));
    }

    /**
     * @param string $email
     * @return bool
     */
    public static function checkEmail(string $email): bool
    {
        return filter_var($email, FILTER_VALIDATE_EMAIL);
    }

    /**
     * @param string $content
     * @return string
     */
    public static function sanitizeWysiwyg(string $content): string
    {
        return trim($content);
    }

    /**
     * @param string $slug
     * @return string
     */
    public static function sanitizeSlug(string $slug): string
    {
        return str_replace(" ", "_",trim($slug));
    }

    /**
     * @param array $inputs
     * @param int $hiddenFields
     * @return int
     */
    public static function countAuthorizedFields(array $inputs, int $hiddenFields=0): int
    {
        $inputs_nb = 0;
        foreach($inputs as $name => $input){
            if(!isset($input['authorized'])){
                $input['authorized'] = true;
            }
            if($input['authorized'] === true){
                ++$inputs_nb;
            }
        }
        return $inputs_nb+$hiddenFields;
    }

    /**
     * @param $commentaire_id
     * @return bool|string
     */
    public static function checkIfCommentExists($commentaire_id): bool|string
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

    /**
     * @param $commentaire_id
     * @return bool|string
     */
    public static function checkIfNotAlreadySignaled($commentaire_id): bool|string
    {
        $signalement = new Signalement();
        if($signalement->findOneBy($signalement->getTable(), ['commentaire_id' => $commentaire_id, 'user_id' => Security::getUser()->getid()])) {
            return "Commentaire déjà signalé";
        }

        return true;
    }

    /**
     * @return bool|string
     */
    public static function checkIfUserSignaledLessThanOneMinuteAgo(): bool|string
    {
        $lastSignalement = new Signalement();
        $lastSignalement = $lastSignalement->findOneBy($lastSignalement->getTable(), ['user_id' => Security::getUser()->getid()], ['date_creation', 'DESC']);
        $difference = date_diff(date_create($lastSignalement->getDateCreation()), date_create(date('Y-m-d H:m:s')))->format('%i');
        if((int)$difference < 25){
            return "Vous ne pouvez pas signaler de commentaire pour l'instant. Veuillez réessayer dans quelques minutes.";
        }

        return true;
    }

    /**
     * @param $commentaire_id
     * @return bool|string
     */
    public static function canSignalComment($commentaire_id): bool|string
    {
        if(self::checkIfCommentExists($commentaire_id) === true
            && self::checkIfNotAlreadySignaled($commentaire_id) === true){
            return true;
        }

        if(self::checkIfCommentExists($commentaire_id) !== true){
            return self::checkIfCommentExists($commentaire_id);
        }
        if(self::checkIfNotAlreadySignaled($commentaire_id) !== true){
            return self::checkIfNotAlreadySignaled($commentaire_id);
        }
        if(self::checkIfUserSignaledLessThanOneMinuteAgo() !== true){
            return self::checkIfUserSignaledLessThanOneMinuteAgo();
        }
        return true;
    }
}

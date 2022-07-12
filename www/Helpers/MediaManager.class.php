<?php

namespace App\Helpers;


use App\Core\Security;
use App\Model\Media;

class MediaManager{

    public static function saveFile(string $filename, mixed $data, object $object)
    {
        if(self::verifyImageType($data['type']) === false){
            Security::return415("Le type de fichier n'est pas autorisé");
        }

        if(self::veridyNameUnicrity($filename) === false){
            Security::return422("Le nom du fichier n'est pas unique");
        }

        $chemin = 'assets/images/'.str_replace(" ", "_",$filename).".".str_replace("image/", "",$data["type"]);
        move_uploaded_file($data['tmp_name'], $chemin);
        $media = new Media();
        $media->setNom($filename);
        $media->setChemin($chemin);
        $media->setStatut(2);
        $media->setUserId($_SESSION['user']['id']);
        return $media->save();
    }

    public static function deleteFile($media_id): void
    {
        $media = new Media();
        $media->setId($media_id);

        $media->delete();
    }

    public static function verifyImageType(string $type): bool
    {
        $allowed = array('image/jpeg', 'image/jpg');
        return in_array($type, $allowed, true);
    }

    public static function veridyNameUnicrity(string $name): bool
    {
        $media = new Media();
        if($media->findOneBy($media->getTable(), ['nom' => $name])){
            return false;
        }

        return true;
    }
}
<?php

namespace App\Helpers;


use App\Core\Security;
use App\Model\Media;

class MediaManager{

    public static function saveFile(string $filename, mixed $data, object $object)
    {
        if(self::verifyImageType($data['type']) === false){
            Security::returnError(415, "Le type de fichier n'est pas autorisé");
        }
        if(self::verifyNameUnicrity($filename) === false){
            Security::returnError(422, "Le nom du fichier n'est pas unique");
        }
        if(self::verifyImageSize($data['size']) === false){
            Security::returnError(413, "Le fichier est trop lourd pour être chargé");
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
        $allowed = array('image/jpeg', 'image/jpg', 'image/png');
        return in_array($type, $allowed, true);
    }

    public static function verifyNameUnicrity(string $name): bool
    {
        $media = new Media();
        if($media->findOneBy($media->getTable(), ['nom' => $name])){
            return false;
        }

        return true;
    }

    public static function verifyImageSize(int $size): bool
    {
        return $size < 640000;
    }

    public static function downloadMedia(int $media_id): void
    {
        $media = new Media();
        $media->setId($media_id);
        $media = $media->findOneBy($media->getTable(), ['id' => $media_id]);

        header('Content-Type: '.end(explode('.', $media->getChemin())));
        header('Content-Disposition: attachment; filename="'.$media->getNom().'"');
        readfile($media->getChemin());
    }
}
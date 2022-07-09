<?php

namespace App\Helpers;


use App\Model\Media;

class MediaManager{

    public static function saveFile(string $filename, mixed $data, object $object)
    {
        $chemin = 'assets/images/'.str_replace(" ", "_",$filename).".".str_replace("image/", "",$data["type"]);

        move_uploaded_file($data['tmp_name'], $chemin);
        $media = new Media();
        $media->setNom($filename);
        $media->setChemin($chemin);
        $media->setStatut(2);
        $media->setUserId($_SESSION['user']['id']);
        return $media->save();
    }
}
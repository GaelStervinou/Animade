<?php

namespace App\Helpers;

use App\Model\Page;
use App\Model\User;
use App\Model\Personnage;
use App\Model\Categorie;
use App\Model\Chapitre;
use App\Model\Commentaire;
use App\Model\Media;
use App\Model\Tag;

class UrlHelper
{
    public static function getUrlParameters($parameters)
    {
        $result = [];
        foreach ($parameters as $param => $value){
            if($param === 'page'){
                $page = new Page();
                $result['page'] = $page->getPageFromSlug($value);
            }
            elseif($param === 'personnage'){
                $personnage = new Personnage();
                $result['personnage'] = $personnage->getPersonnageFromNom($value);
            }
            elseif($param === 'chapitre'){
                $chapitre = new Chapitre();
                $result['chapitre'] = $chapitre->getChapitreFromTitre($value);
            }elseif($param === 'categorie') {
                $categorie = new Categorie();
                $result[ 'categorie' ] = $categorie->findOneBy($categorie->getTable(), ['nom' => $value]);
            }elseif(str_contains($param, '_id')){
                $class = "App\Model\\".ucfirst(str_replace("_id", "", $param));
                $object = new $class();
                $result['object'] = $object->setId((int)$value);
            }
        }
        return $result;
    }

    public static function getSearch($parameters)
    {
        $search = [];
        foreach($parameters as $param => $value){
            $search[str_replace("_id", "", $param)] = self::getObjectToString($param, $value);
        }
        return $search;
    }

    public static function getObjectToString($param, $value)
    {
        $class = "App\Model\\".ucfirst(str_replace("_id", "", $param));

        if(class_exists($class)){
            $object = new $class();
            $object = $object->setId((int)$value);
            return $object->toString();
        }elseif($param == "auteur_id"){
            return self::getObjectToString('user_id', $value);
        }elseif ($param == "parent_id"){
            return self::getObjectToString('categorie_id', $value);
        }
    }

    public static function isAjaxRequest()
    {
        if(!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest')
        {
            return true;
        }

        return false;
    }

    public static function getPageUrl(string $slug): string
    {
        return "http://".$_SERVER['HTTP_HOST']."/page?page=".$slug;
    }
}
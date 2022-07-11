<?php

namespace App\Controller;

use App\Model\Page;
use App\Model\User;
use App\Model\Personnage;
use App\Model\Categorie;
use App\Core\View;

class Recherche{

    public function recherche()
    {
        $recherche = $_GET['recherche'];
        $resultats = [];

        $resultats['pages'] = $this->recherchePages($recherche);
        $resultats['auteurs'] = $this->rechercheAuteurs($recherche);
        $resultats['personnages'] = $this->recherchePersonnages($recherche);
        $resultats['categories'] = $this->rechercheCategories($recherche);

        $view = new View("recherche/displayRecherche");
        $view->assign("resultats", $resultats);
        return true;
    }

    public function recherchePages($recherche)
    {
        $page = new Page();
        $pagesTitre = $page->findManyBy([
            'titre' => [
                "operator" =>' LIKE ',
                "value" => '%'.$recherche.'%',
            ],
            'statut' => 2,
        ]);
        $pagesSlug = $page->findManyBy([
            'slug' => [
                "operator" =>' LIKE ',
                "value" => '%'.$recherche.'%',
            ],
            'statut' => 2,
        ]);
        $pagesDescription = $page->findManyBy([
            'description' => [
                "operator" =>' LIKE ',
                "value" => '%'.$recherche.'%',
            ],
            'statut' => 2,
        ]);
        $pages = array_merge($pagesTitre, $pagesSlug, $pagesDescription);
        $pageList = [];
        foreach($pages as $page){
            if(empty($pageList[$page->getId()])){
                $pageList[$page->getId()] = $page;
            }
        }
        return $pageList;
    }

    public function rechercheAuteurs($recherche)
    {
        $user = new User();
        $userFirstName = $user->findManyBy([
            'firstname' => [
                "operator" =>' LIKE ',
                "value" => '%'.$recherche.'%',
                "is_utf8" => 1,
            ],
            'status' => 2,
        ]);

        $userLastName = $user->findManyBy([
            'lastname' => [
                "operator" =>' LIKE ',
                "value" => '%'.$recherche.'%',
                "is_utf8" => 1,
            ],
            'status' => 2,
        ]);

        $users = array_merge($userFirstName, $userLastName);
        $userList = [];
        foreach($users as $user){
            if(empty($userList[$user->getId()])){
                $userList[$user->getId()] = $user;
            }
        }
        return $userList;
    }

    public function recherchePersonnages($recherche)
    {
        $personnage = new Personnage();
        $personnages = $personnage->findManyBy([
            'nom' => [
                "operator" =>' LIKE ',
                "value" => '%'.$recherche.'%',
            ],
            'statut' => 2,
        ]);

        return $personnages;
    }

    public function rechercheCategories($recherche)
    {
        $categorie = new Categorie();
        $categoriesNom = $categorie->findManyBy([
            'nom' => [
                "operator" =>' LIKE ',
                "value" => '%'.$recherche.'%',
            ],
            'statut' => 2,
        ]);

        $categoriesDescription = $categorie->findManyBy([
            'description' => [
                "operator" =>' LIKE ',
                "value" => '%'.$recherche.'%',
            ],
            'statut' => 2,
        ]);
        $categories = array_merge($categoriesNom, $categoriesDescription);
        $categorieList = [];
        foreach($categories as $categorie){
            if(empty($categorieList[$categorie->getId()])){
                $categorieList[$categorie->getId()] = $categorie;
            }
        }
        return $categorieList;
    }
}
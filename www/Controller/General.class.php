<?php

namespace App\Controller;

use App\Core\Security;
use App\Core\View;
use App\Helpers\UrlHelper;
use App\Model\Page;
use App\Model\Chapitre;

class General{

    public function home()
    {
        $view = new View("accueil");
        $view->assign("user", Security::getUser());

        $page = new Page();
        $pages = $page->findManyBy(['statut' => 2], ['date_creation', 'DESC'], [0, 5]);
        $view->assign("pages", $pages);

        $firstday = date('Y/m/d', strtotime("sunday -1 week"));

        $pages = $page->findManyBy(['statut' => 2, 'date_creation' => [
            "operator" =>' >= ',
            "value" => $firstday,
        ],]);
        $mostLikedPage = "";
        $likeCounter = 0;
        foreach ($pages as $page) {
            if ($page->countLikes() > $likeCounter) {
                $likeCounter = $page->countLikes();
                $mostLikedPage = $page;
            }
        }
        $view->assign("mostLikedPage", $mostLikedPage);
        $lastChapitre = new Chapitre();
        $lastChapitre = $lastChapitre->findOneBy($lastChapitre->getTable(), ['statut' => 2], ['id', 'DESC']);
        $view->assign("lastChapitre", $lastChapitre);
    }

    public function contact()
    {
        $view = new View("contact");
    }

    public function sitemap()
    {
        $page = new Page();
        $pages = $page->findManyBy(['statut' => 2]);
        $xml = new \SimpleXMLElement("<?xml version='1.0' encoding='UTF-8' ?>\n"."<urlset xmlns='http://www.sitemaps.org/schemas/sitemap/0.9' />");
        foreach($pages as $page){
            $url = $xml->addChild('url');
            $url->addChild('loc', UrlHelper::getPageUrl($page->getSlug()));
            $url->addChild('lastmod', $page->getDateModification());
            $url->addChild('changefreq', 'daily');
            $url->addChild('priority', '0.8');
        }

        $view = new View("sitemap", "without");
        $view->assign("xml", $xml);
    }
}

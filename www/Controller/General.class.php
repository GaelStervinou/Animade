<?php

namespace App\Controller;

use App\Core\View;
use App\Helpers\UrlHelper;
use App\Model\Page;

class General{

    public function home()
    {
        echo "Welcome";
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

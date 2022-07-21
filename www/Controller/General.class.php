<?php

namespace App\Controller;

use App\Core\Exception;
use App\Core\PHPMailer;
use App\Core\Security;
use App\Core\Validator;
use App\Core\View;
use App\Helpers\Formalize;
use App\Helpers\UrlHelper;
use App\Model\Page;
use App\Model\Chapitre;
use App\Model\User;

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
        $view->assign("meta",
            [
                'script' => ['../dist/js/datatable.js'],
                'titre' => 'Accueil',

            ]);
    }

    public function sitemap()
    {
        $page = new Page();
        $pages = $page->findManyBy(['statut' => 2]);
        $xml = new \SimpleXMLElement("<?xml version='1.0' encoding='UTF-8' ?>\n"."<urlset xmlns='http://www.sitemaps.org/schemas/sitemap/0.9' />");

        $url = $xml->addChild('url');
        $url->addChild('loc', 'http://'.$_SERVER['HTTP_HOST'].'/login');
        $url->addChild('changefreq', 'yearly');
        $url->addChild('priority', '1');

        $url = $xml->addChild('url');
        $url->addChild('loc', 'http://'.$_SERVER['HTTP_HOST'].'/contact');
        $url->addChild('changefreq', 'yearly');
        $url->addChild('priority', '1');

        foreach($pages as $page){
            $url = $xml->addChild('url');
            $url->addChild('loc',UrlHelper::getPageUrl($page->getSlug()));
            if(!empty($page->getDateModification())){
                $url->addChild('lastmod',  Formalize::formalizeDateYearMonthDay($page->getDateModification()));
            }
            $url->addChild('changefreq', 'daily');
            $url->addChild('priority', '0.5');
        }

        $view = new View("sitemap", "without");
        $view->assign("xml", $xml);
    }

    public function contact()
    {
        if(!empty($_POST)){

            $result = Validator::run($this->contactForm(), $_POST);
            if(empty($result)){
                $mail = new PHPMailer(true);
                $mail->CharSet = 'UTF-8';
                $options = [
                    'subject' => SITENAME.' - Contact : '.$_POST['nom'],
                    'body' => "{$_POST['nom']} vous a contacté via le formulaire de contact du site ".SITENAME.".\n\n".
                        "Email : {$_POST['email']}\n\n".
                        "Message : {$_POST['message']}",
                ];

                try {
                    foreach((new User())->findManyBy(['role_id' => 4]) as $admin){
                        $mail->sendEmail($admin->getEmail(), $options);
                    }
                }catch (Exception $e){
                    Security::returnError(400, "Erreur lors de l'envoi du mail");
                }
                if(Security::isConnected()){
                    header("Location:/");
                }else{
                    header('Location:/login');
                }
            }else {
                Security::returnError(400, implode("\r\n", $result));
            }
        }else{
            $view = new View("contact");
            $view->assign('contactForm', $this->contactForm());
        }
    }

    public function contactForm()
    {
        $form =
            [
                'config' => [
                    'method' => 'POST',
                    'action' => '',
                    'submit' => "Envoyer",
                    'title' => "Nous contacter",
                ],
                'inputs' => [
                    'nom' => [
                        'type' => 'text',
                        'label' => 'Nom',
                        'placeholder' => 'Votre nom',
                        'required' => true,
                    ],
                    'email' => [
                        'type' => 'email',
                        'label' => 'Email',
                        'placeholder' => 'Votre email',
                        'required' => true,
                    ],
                    'message' => [
                        'type' => 'textarea',
                        'label' => 'Message',
                        'placeholder' => 'Votre message',
                        'required' => true,
                    ],
                    ]
            ];

        $user = Security::getUser();
        if ($user !== false) {
            $form['inputs']['nom']['default_value'] = $user->getFullName();
            $form['inputs']['email']['default_value'] = $user->getEmail();
        }
        return $form;
    }
}

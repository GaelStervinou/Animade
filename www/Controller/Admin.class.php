<?php

namespace App\Controller;

use App\Core\Security;
use App\Core\Validator;
use App\Core\View;
use App\Model\Signalement as SignalementModel;
use App\Model\User as UserModel;
use App\Model\Page;
use PDO;
use PDOException;

class Admin
{
    public function dashboard()
    {
        $view = new View("dashboard", "back");

        $user = Security::getUser();
        $view->assign("user", $user);

        $page = new Page();
        $pages = $page->findManyBy(['statut' => 2]);
        $view->assign("pages", $pages);

        $users = $user->findManyBy([
            'status' => [
                'operator' => '!=',
                'value' => -1
            ]
        ]);
        $view->assign("users", $users);
        $view->assign("meta",
        [
            'script' => ['../dist/js/dataTable.js'],
            'titre' => 'Dashboard',

        ]);

        $signalements = $this->getSignalementsCommentaireUnique();
        $view->assign("signalements", $signalements);
    }

    public function listUsers()
    {
        $user = new UserModel();
        $users = $user->findManyBy([]);
        $view = new View("admin/listUsers", "back");
        $view->assign("users", $users);
        $view->assign("meta", [
            'script' => [
                "../dist/js/dataTable.js",
            ],
            'titre' => 'Modération des utilsateurs',
        ]);
    }

    public function listSignalements()
    {
        $signalements = $this->getSignalementsCommentaireUnique();
        $view = new View("admin/listSignalements");
        $view->assign("signalements", $signalements);
        $view->assign("meta", [
            'script' => [
                "../dist/js/dataTable.js",
            ],
            'titre' => 'Modération des commentaires',
        ]);
    }

    public function getSignalementsCommentaireUnique()
    {
        $signalementRequest = new SignalementModel();
        $signalements = $signalementRequest->findManyBy(['statut' => 2], ['date_creation', 'DESC']);

        $listCommentaires = [];
        foreach ($signalements as $key => $signalement) {
            $commentaire_id = $signalement->getCommentaireId();
            if ( $commentaire_id !== null && !in_array($commentaire_id, $listCommentaires)){
                $listCommentaires[] = $commentaire_id;
            }else{
                unset($signalements[$key]);
            }
        }

        return $signalements;
    }

    public function administration(): void
    {

        if(!empty($_POST)){
            if (!empty($_FILES)) {
                foreach ($_FILES as $name => $info) {
                    $_POST[$name] = $info;
                }
            }
            $user = new UserModel();

            $result = Validator::run($user->getSettingsForm(), $_POST);
            if(empty($result)){
                $configContent = "<?php\n";

                if(!empty($_POST["FAVICON"])){
                    move_uploaded_file($_POST["FAVICON"]['tmp_name'], "assets/images/favicon.".str_replace("image/", "",$_POST["FAVICON"]["type"]));
                    $_POST["FAVICON"] = "favicon.".str_replace("image/", "",$_POST["FAVICON"]["type"]);
                }

                foreach($_POST as $key => $value){
                    $configContent .= "define(\"".$key."\", \"".$value."\");\n";
                }
                file_put_contents('conf.inc.php', $configContent);
            }

            header("Location:/admin/dashboard");
        }else{
            $view = new View("admin/manager", "back");
            $user = Security::getUser();
            $view->assign("user", $user);
        }

    }

    public static function getSettings()
    {
        $fileContent = file_get_contents('conf.inc.php');
        $settings = [];
        foreach (explode("\n", $fileContent) as $line) {
            preg_match('/(".{0,150}"), (".{0,150}")/', $line, $matches);
            if(!empty($matches)){
                $settings[str_replace('"', '', $matches[1])] = str_replace('"', '', $matches[2]);
            }
        }
        return $settings;
    }

    public function install()
    {

        if(!empty($_POST)){
            $result = Validator::run($this->getInstallForm(), $_POST);

            if(empty($result)){
                if(!str_ends_with($_POST[ 'DBPREFIX' ], "_")){
                    $_POST['DBPREFIX'] .= "_";
                }
                $configContent = "<?php\n";

                foreach($_POST as $key => $value){
                    if(!str_contains($key, 'SUPERADMIN')){
                        $configContent .= "define(\"".$key."\", \"".$value."\");\n";
                    }
                }
                file_put_contents('conf.inc.php', $configContent);
            }
            try {
                $db = $_POST['DBNAME'];
                $prefix = $_POST['DBPREFIX'];
                $host = $_POST['DBHOST'];
                $root = $_POST['DBUSER'];
                $root_password = $_POST['DBPWD'];

                $dbh = new PDO("mysql:host=$host", $root, $root_password);

                $dbh->exec(
                    "CREATE DATABASE `$db`;
                    USE `$db`;

SET SQL_MODE = \"NO_AUTO_VALUE_ON_ZERO\";
START TRANSACTION;
SET time_zone = \"+00:00\";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

CREATE TABLE {$prefix}categorie (
  `id` int(11) NOT NULL,
  `parent_id` int(11) DEFAULT NULL,
  `nom` varchar(50) NOT NULL,
  `description` varchar(250) NOT NULL,
  `statut` tinyint(4) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE {$prefix}chapitre (
  `id` int(11) NOT NULL,
  `titre` varchar(75) NOT NULL,
  `media_id` int(11) DEFAULT NULL,
  `statut` tinyint(4) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE {$prefix}commentaire (
  `id` int(11) NOT NULL,
  `contenu` varchar(1000) NOT NULL,
  `auteur_id` int(11) NOT NULL,
  `commentaire_id` int(11) DEFAULT NULL,
  `page_id` int(11) DEFAULT NULL,
  `media_id` int(11) DEFAULT NULL,
  `statut` tinyint(4) NOT NULL,
  `date_creation` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE {$prefix}like (
  `id` int(11) NOT NULL,
  `aime` tinyint(1) NOT NULL,
  `user_id` int(11) NOT NULL,
  `page_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE {$prefix}media (
  `id` int(11) NOT NULL,
  `nom` varchar(65) NOT NULL,
  `description` varchar(250) DEFAULT NULL,
  `chemin` varchar(75) NOT NULL,
  `statut` tinyint(4) NOT NULL,
  `user_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE {$prefix}page (
  `id` int(11) NOT NULL,
  `titre` varchar(100) CHARACTER SET latin1 NOT NULL,
  `contenu` varchar(25000) CHARACTER SET latin1 NOT NULL,
  `description` varchar(250) CHARACTER SET latin1 DEFAULT NULL,
  `slug` varchar(25) CHARACTER SET latin1 NOT NULL,
  `auteur_id` int(11) NOT NULL,
  `personnage_id` int(11) DEFAULT NULL,
  `media_id` int(11) DEFAULT NULL,
  `date_creation` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `date_modification` timestamp NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
  `statut` tinyint(4) NOT NULL,
  `chapitre_id` int(11) DEFAULT NULL,
  `categorie_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE {$prefix}personnage (
  `id` int(11) NOT NULL,
  `nom` varchar(65) CHARACTER SET latin1 NOT NULL,
  `media_id` int(11) DEFAULT NULL,
  `statut` tinyint(4) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE {$prefix}signalement (
  `id` int(11) NOT NULL,
  `commentaire_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `statut` tinyint(4) DEFAULT '0',
  `date_creation` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE {$prefix}user (
  `id` int(11) NOT NULL,
  `firstname` varchar(50) NOT NULL,
  `lastname` varchar(100) NOT NULL,
  `email` varchar(320) NOT NULL,
  `password` varchar(255) NOT NULL,
  `status` tinyint(4) NOT NULL DEFAULT '0',
  `token` char(32) DEFAULT NULL,
  `media_id` int(11) DEFAULT NULL,
  `createdAt` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updatedAt` timestamp NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
  `emailToken` char(80) NOT NULL,
  `mdpToken` char(80) DEFAULT NULL,
  `role_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


ALTER TABLE {$prefix}categorie
  ADD PRIMARY KEY (`id`);

ALTER TABLE {$prefix}chapitre
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `titre` (`titre`);

ALTER TABLE {$prefix}commentaire
  ADD PRIMARY KEY (`id`);

ALTER TABLE {$prefix}like
  ADD PRIMARY KEY (`id`);

ALTER TABLE {$prefix}media
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `nom` (`nom`);

ALTER TABLE {$prefix}page
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `slug` (`slug`);

ALTER TABLE {$prefix}personnage
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `nom` (`nom`);

ALTER TABLE {$prefix}signalement
  ADD PRIMARY KEY (`id`);

ALTER TABLE {$prefix}user
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

ALTER TABLE {$prefix}categorie
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

ALTER TABLE {$prefix}chapitre
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

ALTER TABLE {$prefix}commentaire
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

ALTER TABLE {$prefix}like
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

ALTER TABLE {$prefix}media
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

ALTER TABLE {$prefix}page
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

ALTER TABLE {$prefix}personnage
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

ALTER TABLE {$prefix}signalement
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

ALTER TABLE {$prefix}user
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
")
                or die(print_r($dbh->errorInfo(), true));

            }catch (PDOException $e) {
                die("DB ERROR: " . $e->getMessage());
            }
            include('conf.inc.php');

            $user = new UserModel();
            $user->beginTransaction();
            $user->setFirstname($_POST['SUPERADMIN']);
            $user->setLastname('Super Admin');
            $user->setEmail($_POST['SUPERADMINEMAIL']);
            $user->setPassword($_POST['SUPERADMINPWD']);
            $user->setStatus(2);
            $user->setRoleId(4);
            $user->generateEmailToken();

            $user->save();
            $user->commit();
            Security::logout();
        }else{
            $view = new View("install", "without");

            $view->assign("installForm", $this->getInstallForm());
        }
    }

    public function getInstallForm()
    {
        return [
            'config' => [
                'method' => 'POST',
                'action' => '',
                'submit' => "Installer",
                'title' => "Installation",
            ],
            'inputs' => [
                'DBNAME' => [
                    'type' => 'text',
                    'label' => 'Nom de la base de données:',
                    'placeholder' => 'Nom de la base de données',
                    'id' => 'dbNameAdmin',
                    'class' => 'inputRegister',
                    'min' => 2,
                    'max' => 150,
                    'required' => true,
                    'error' => "Le nom n'est pas conrrect",
                ],
                'DBUSER' => [
                    'type' => 'text',
                    'label' => 'Nom de l\'utilisateur de la base:',
                    'placeholder' => 'Nom de l\'utilisateur de la base',
                    'id' => 'dbUserAdmin',
                    'class' => 'inputRegister',
                    'min' => 2,
                    'max' => 150,
                    'required' => true,
                    'error' => "Le nom n'est pas conrrect",
                    'default_value' => 'root',
                ],
                'DBPWD' => [
                    'type' => 'text',
                    'label' => 'Mot de passe de la base:',
                    'placeholder' => 'Mot de passe de la base',
                    'id' => 'dbPwdAdmin',
                    'class' => 'inputRegister',
                    'min' => 2,
                    'max' => 150,
                    'required' => true,
                    'error' => "Le nom n'est pas conrrect",
                    'default_value' => 'password',
                ],
                'DBDRIVER' => [
                    'type' => 'text',
                    'label' => 'Driver de la base:',
                    'placeholder' => 'Driver de la base',
                    'id' => 'dbDriverAdmin',
                    'class' => 'inputRegister',
                    'min' => 2,
                    'max' => 150,
                    'required' => true,
                    'error' => "Le nom n'est pas conrrect",
                    'default_value' => 'mysql',
                ],
                'DBPORT' => [
                    'type' => 'text',
                    'label' => 'Port de la base:',
                    'placeholder' => 'Port de la base',
                    'id' => 'dbPortAdmin',
                    'class' => 'inputRegister',
                    'min' => 2,
                    'max' => 150,
                    'required' => true,
                    'error' => "Le nom n'est pas conrrect",
                    'default_value' => '3306',
                ],
                'DBHOST' => [
                    'type' => 'text',
                    'label' => 'Host de la base:',
                    'placeholder' => 'Host de la base',
                    'id' => 'dbHostAdmin',
                    'class' => 'inputRegister',
                    'min' => 2,
                    'max' => 150,
                    'required' => true,
                    'error' => "Le nom n'est pas correct",
                    'default_value' => 'database',
                ],
                'DBPREFIX' => [
                    'type' => 'text',
                    'label' => 'Prefix des tables:',
                    'placeholder' => 'Prefix des tables',
                    'id' => 'dbPrefixAdmin',
                    'class' => 'inputRegister',
                    'min' => 2,
                    'max' => 150,
                    'required' => true,
                    'error' => "Le nom n'est pas conrrect",
                ],
                'SUPERADMIN' => [
                    'type' => 'text',
                    'label' => 'Nom du super-utilisateur:',
                    'placeholder' => 'Nom du super-utilisateur',
                    'id' => 'suerpAdminAdmin',
                    'class' => 'inputRegister',
                    'min' => 2,
                    'max' => 150,
                    'required' => true,
                    'error' => "Le nom n'est pas conrrect",
                    'default_value' => 'SUPERADMIN',
                ],
                'SUPERADMINPWD' => [
                    'type' => 'password',
                    'label' => 'Mot de passe :',
                    'placeholder' => 'Votre mot de passe',
                    'id' => 'pwdUpdate',
                    'class' => 'inputRegister',
                    'required' => true,
                    'error' => 'Votre mot de passe doit faire entre 8 et 16 caractères et contenir des chiffres et des lettres',
                ],
                'SUPERADMINEMAIL' => [
                    'type' => 'email',
                    'label' => 'Email :',
                    'placeholder' => 'Votre email',
                    'id' => 'emailRegister',
                    'class' => 'inputRegister',
                    'required' => true,
                    'error' => 'Email incorrect',
                    'errorUnicity' => 'Email existe déjà en bdd',
                    'unicity' => true,
                ],
                'SMTP_USERNAME' => [
                    'type' => 'text',
                    'label' => 'Utilisateur du SMTP:',
                    'placeholder' => 'Utilisateur du SMTP',
                    'id' => 'smtpUserAdmin',
                    'class' => 'inputRegister',
                    'min' => 2,
                    'max' => 150,
                    'required' => true,
                    'error' => "Le nom n'est pas conrrect",
                ],
                'SMTP_PASSWORD' => [
                    'type' => 'text',
                    'label' => 'Mot de passe du SMTP:',
                    'placeholder' => 'Mot de passe du SMTP',
                    'id' => 'smtpPwdAdmin',
                    'class' => 'inputRegister',
                    'min' => 2,
                    'max' => 150,
                    'required' => true,
                    'error' => "Le nom n'est pas conrrect",
                ],
                'SMTP_HOST' => [
                    'type' => 'text',
                    'label' => 'Host du SMTP:',
                    'placeholder' => 'Host du SMTP',
                    'id' => 'smtpHostAdmin',
                    'class' => 'inputRegister',
                    'min' => 2,
                    'max' => 150,
                    'required' => true,
                    'error' => "Le nom n'est pas conrrect",
                    'default_value' => 'ssl://smtp.gmail.com',
                ],
                'CONTACT_MAIL' => [
                    'type' => 'text',
                    'label' => 'Mail de contact:',
                    'placeholder' => 'Mail de contact',
                    'id' => 'contactMailAdmin',
                    'class' => 'inputRegister',
                    'min' => 2,
                    'max' => 150,
                    'required' => true,
                    'error' => "Le nom n'est pas conrrect",
                ],
            ],
        ];
    }
}
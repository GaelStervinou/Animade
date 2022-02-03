<?php
// Inclure les fichiers phpmailer
require 'includes/PHPMailer.php';
require 'includes/SMTP.php';
require 'includes/Exception.php';
//Définition des namespaces
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

//Créer une instance de phpmailer
$mail = new PHPMailer(true);

try {
    //Configuration
    $mail->SMTPDebug = SMTP::DEBUG_SERVER; //Informations de debug

    // On configure le SMTP
    $mail->isSMTP();
    $mail->Host ="ssl://smtp.gmail.com";
    $mail->Port = 465;
    $mail->SMTPAuth = true;
    $mail->Username = "thomasesgipa@gmail.com";
    $mail->Password = "gfGYF3XD8@dgDcFJ";


    //Charset
    $mail->Charset = "utf-8";

    //Destinataires
    $mail->addAddress("thomasesgipa@gmail.com");

    //Expéditeur
    $mail->setFrom("thomasesgipa@gmail.com");

    //Contenu
    $mail->Subject = "Test envoi validation adresse email";
    $mail->Body = "Bonjour, veuillez valider votre adresse email en cliquant sur le lien suivant.";

    //On envoie le mail
    $mail->send();
    echo "Mail envoyé correctement";

}catch(Exception $e){
    echo "Message non envoyé. Erreur: {$mail->ErrorInfo}";

}

?>

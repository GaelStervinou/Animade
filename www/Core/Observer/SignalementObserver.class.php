<?php

namespace App\Core\Observer;

use App\Core\PHPMailer;
use App\Model\Signalement;
use App\Model\User;

class SignalementObserver
{
    protected array $admins;
    protected Signalement $signalement;

    public function __construct(Signalement $signalement)
    {
        $this->signalement = $signalement;
        $this->setAdmins();
    }
    public function setAdmins()
    {
        $admins = (new User())->findManyBy(['role_id' => 3]);
        $amins = array_merge($admins, (new User())->findManyBy(['role_id' => 4]));
        $this->admins = $admins;
    }

    public function getAdmins()
    {
        return $this->admins;
    }

    public function getSignalement()
    {
        return $this->signalement;
    }
    public function update(SignalementSubject $signalement): void
    {
        foreach($this->getAdmins() as $admin => $user) {
            $mail = new PHPMailer(true);
            $mail->CharSet = 'UTF-8';
            $options = [
                'subject' => 'Nouveau signalement',
                'body' => "Bonjour, un nouveau commentaire a été signalé sur " . SITENAME . " : http://{$_SERVER['HTTP_HOST']}/commentaire?commentaire_id={$this->getSignalement()->getCommentaire()->getId()}",
            ];
            $mail->sendEmail($user->getEmail(), $options);
        }
    }
}
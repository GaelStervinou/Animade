<?php

namespace App\Core;

use App\Model\Commentaire;

class SignalementObserver implements \SplObserver
{
    public function update(\SplSubject $subject)
    {

    }

    // TODO count le nb de signalement à chaque nouveau signalement, et tous les 10 signalements on envoie un mail à l'admin
}
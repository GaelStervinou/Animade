<?php

namespace App\Core\Observer;

use App\Model\Commentaire;
use App\Model\Signalement;
use App\Model\User;
use App\Core\PHPMailer;
use App\Core\Observer\SignalementObserver;

class SignalementSubject
{
    protected $_signalements;

    public function notify(): void
    {
        foreach ($this->_signalements as $observer) {
            $observer->update($this);
        }
    }

    public function attach(SignalementObserver $observer): void
    {
        $this->_signalements[$observer->getSignalement()->getId()] = $observer;
    }

    public function detach(SignalementObserver $observer): void
    {
        unset($this->_signalements[$observer->getSignalement()->getId()]);
    }
}
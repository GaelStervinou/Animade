<?php

namespace App\Core;

use SplObserver;

class SignalementObserverSubject implements \SplSubject{

    protected $observers = [];

    public function __construct()
    {
        $this->observers = new \SplObjectStorage();
    }
    public function attach(SplObserver $observer)
    {
        $this->getObservers()->attach($observer);
    }

    public function detach(SplObserver $observer)
    {
        $this->getObservers()->detach($observer);
    }

    public function notify()
    {
        foreach($this->getObservers() as $observer)
        {
            $observer->update($this);
        }
    }

    public function getObservers()
    {
        return $this->observers;
    }
}
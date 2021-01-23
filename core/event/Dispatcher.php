<?php


namespace App\Core\Event;


class Dispatcher
{
    private $listeners = [];

    public function dispatch(object $event, string $eventName = null)
    {
        dump($event);
        dump($eventName);
    }

    public function addListener($listener, string $eventName)
    {
        $this->listeners[$eventName][] = $listener;
    }
}
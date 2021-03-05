<?php


namespace Core\Event\listeners;

use Core\Event\Dispatcher;

class FakeListener
{
    /**
     * @var Dispatcher
     */
    public $dispatcher;

    public function setListener(string $eventName, $method, Dispatcher $dispatcher)
    {
        $listenerData[] = __CLASS__;
        $listenerData[] = $method;
        $dispatcher->addListener($listenerData, $eventName);
    }

    public function onResponse(object $event, string $eventName)
    {
    }
}

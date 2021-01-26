<?php


namespace App\core\event\listeners;

use App\Core\Event\Dispatcher;

class RouterListener
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

    public function onRequest(object $event, string $eventName)
    {
        $request = $event->getRequest();
        dd($request);
    }
}

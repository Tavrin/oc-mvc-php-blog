<?php


namespace App\core\event\listeners;

use App\Core\Event\Dispatcher;
use App\core\event\events\RequestEvent;
use App\core\routing\Router;
use http\Message\Body;

class RouterListener
{
    /**
     * @var Dispatcher
     */
    public $dispatcher;

    /**
     * @var Router
     */
    public $router;

    public function __construct()
    {
        $this->router = new Router();
    }

    public function setListener(string $eventName, $method, Dispatcher $dispatcher)
    {
        $listenerData[] = __CLASS__;
        $listenerData[] = $method;
        $dispatcher->addListener($listenerData, $eventName);

    }

    public function onRequest(RequestEvent $event, string $eventName)
    {
        $request = $event->getRequest();

        $params =  $this->router->match($request);

        $request->addAttribute($params);
    }
}

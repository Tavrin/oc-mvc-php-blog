<?php


namespace Core\Event\listeners;

use Core\Event\Dispatcher;
use Core\Event\Events\RequestEvent;
use Core\routing\Router;

class RouterListener
{
    /**
     * @var Dispatcher
     */
    public Dispatcher $dispatcher;

    /**
     * @var Router
     */
    public Router $router;

    public function __construct()
    {
        $this->router = new Router();
    }

    public function setListener(string $eventName, $method, Dispatcher $dispatcher)
    {
        $listenerData[] = $this;
        $listenerData[] = $method;
        $dispatcher->addListener($listenerData, $eventName);
    }

    public function onRequest(RequestEvent $event)
    {
        $request = $event->getRequest();

        $params =  $this->router->match($request);

        $request->addAttribute($params);
    }
}

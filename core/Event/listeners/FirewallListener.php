<?php


namespace Core\Event\listeners;


use Core\Event\Dispatcher;
use Core\Event\Events\RequestEvent;
use Core\security\Firewall;

class FirewallListener
{
    /**
     * @var Dispatcher
     */
    public Dispatcher $dispatcher;

    /**
     * @var Firewall
     */
    public Firewall $firewall;

    public function __construct()
    {
        $this->firewall = new Firewall();
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

        $this->firewall->checkFirewalls($request);

    }
}
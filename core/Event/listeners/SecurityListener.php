<?php


namespace Core\Event\listeners;


use Core\Event\Dispatcher;
use Core\Event\Events\RequestEvent;
use Core\security\Firewall;
use Core\security\Security;

class SecurityListener
{
    public Security $security;

    public function __construct()
    {
        $this->security = new Security();
    }

    public function setListener(string $eventName, $method, Dispatcher $dispatcher)
    {
        $listenerData[] = $this;
        $listenerData[] = $method;
        $dispatcher->addListener($listenerData, $eventName);
    }

    public function onRequest(RequestEvent $event)
    {
        $kernel = $event->getKernel();
        $this->security->verifyLoggedUser($kernel);
    }
}
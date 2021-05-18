<?php


namespace Core\Event;


class Dispatcher
{
    private $listeners = [];

    public function dispatch(object $event, string $eventName = null)
    {
        $listenersList = $this->getListeners($eventName);

        foreach ($listenersList as $listener) {
            $className = $listener[0];
            $methodeName = $listener[1];
            $className->$methodeName($event, $eventName);
        }
    }

    public function addListener(array $listener, string $eventName)
    {
        $this->listeners[$eventName][] = $listener;
    }

    public function getListeners(string $eventName = null): array
    {
        if (null === $eventName || empty($this->listeners[$eventName])) {
            return [];
        }

        $listenersList = [];

        foreach ($this->listeners[$eventName] as $listener) {
            $listenersList[] = $listener;
        }

        return $listenersList;
    }
}
